<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\File;
use App\Models\PasswordZip;
use Illuminate\Support\Facades\Crypt;
use App\Models\Archive;
use App\Models\Configuration;
use App\Models\ZipDirectory;
use Log;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $zips = Archive::where('hospitalRecordId', 'like', '%' . $search . '%')
            ->paginate(10);
        return view('zips', compact('zips'));
    }

    public function store(Request $request)
    {
        $folderName = now()->format('YmdHis');
        $selectedFiles = $request->input('files');

        if (!is_array($selectedFiles)) {
            return redirect()->back()->withInput()->withErrors(['error' => 'No files selected']);
        }

        $filesToZip = [];
        foreach ($selectedFiles as $fileId) {
            $file = File::findOrFail($fileId);
            $path = $file->file;
            $hospitalRecordId = $file->hospitalRecordId;

            $passwordZip = PasswordZip::where('hospitalRecordId', $hospitalRecordId)->first();
            if (!$passwordZip) {
                return redirect()->back()->withInput()->withErrors(['error' => 'Please set password']);
            }

            $password = decrypt($passwordZip->password);
            $path = str_replace('/storage/', '', $path);
            $absolutePath = storage_path('app/public/' . $path);

            // No decryption of file content
            $fileContent = file_get_contents($absolutePath);

            $filesToZip[] = ['content' => $fileContent, 'password' => $password, 'filename' => basename($absolutePath)];
        }

        if (empty($filesToZip)) {
            return redirect()->back()->withInput()->withErrors(['error' => 'No files selected.']);
        }

        $zip = new ZipArchive();
        $zipDirectory = public_path('storage/zip_files');
        if (!file_exists($zipDirectory)) {
            mkdir($zipDirectory, 0775, true);
        }
        // Using hospitalRecordId and current date for zip file name
        $zipFileName = $zipDirectory . '/' . $hospitalRecordId . '_' . now()->format('Ymd') . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($filesToZip as $file) {
                $zip->addFromString($file['filename'], $file['content']);
                $zip->setEncryptionName($file['filename'], ZipArchive::EM_AES_256, $file['password']);
            }
            $zip->close();

            Archive::create([
                'zip' => '/storage/zip_files/' . $hospitalRecordId . '_' . now()->format('Ymd') . '.zip',
                'hospitalRecordId' => $hospitalRecordId,
            ]);

            // Add a success message to the session
            return redirect()->back()->with('success', 'Zip file created');
        } else {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create the zip file.']);
        }
    }

    public function download($id)
    {
        $file = Archive::where("id", $id)->firstOrFail();
        $path = $file->zip;

        // Remove the '/storage/' from the beginning of the path
        $path = str_replace('/storage/', '', $path);

        // Get the file contents
        $absolutePath = storage_path('app/public/' . $path);
        $contents = file_get_contents($absolutePath);

        // Generate a temporary file with the contents
        $tempPath = tempnam(sys_get_temp_dir(), 'dec');
        file_put_contents($tempPath, $contents);

        // Download the file from the temporary path
        return response()->download($tempPath, basename($path))->deleteFileAfterSend(true);
    }

    public function downloadPath($id)
    {
        try {
            $file = Archive::where("id", $id)->firstOrFail();
            $path = $file->zip;

            // Remove the '/storage/' from the beginning of the path
            $path = str_replace('/storage/', '', $path);

            // Define the target directory (mapped drive)
            $directory = ZipDirectory::first();
            $targetDirectory = $directory->path;

            // Get the file contents
            $absolutePath = storage_path('app/public/' . $path);
            $contents = file_get_contents($absolutePath);

            // Define the target file path
            $targetFilePath = $targetDirectory . basename($path);

            // Write the contents to the target file
            file_put_contents($targetFilePath, $contents);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Zip has been saved to ' . $targetFilePath);
        } catch (\Exception $e) {
            // Log::error('Failed to save file: ' . $e->getMessage());
            // Redirect back with an error message
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateStoragePath(Request $request)
    {
        $request->validate([
            'storage_path' => 'required|string',
        ]);

        ZipDirectory::updateOrCreate(
            [],
            ['path' => $request->storage_path]
        );

        return redirect()->back()->with('success', 'Storage path updated successfully.');
    }


    public function updateStartingValue(Request $request)
    {
        $request->validate([
            'starting_value' => 'required|integer|min:1',
        ]);

        // Update the starting value in the database
        Configuration::updateOrCreate(
            ['key' => 'filenumber.starting_value'],
            ['value' => $request->starting_value]
        );

        return redirect()->back()->with('success', 'Starting value updated successfully.');
    }

    public function setting()
    {
        // Fetch the starting value configuration
        $starting = Configuration::firstWhere('key', 'filenumber.starting_value');

        // Check if $starting is null
        $startingValue = $starting ? $starting->value : null;

        $storage = ZipDirectory::all()->first();

        // Return the view with both the starting value and the storage path
        return view('setting', compact('startingValue', 'storage'));
    }
}
