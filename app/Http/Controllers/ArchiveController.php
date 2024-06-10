<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use App\Models\File;
use App\Models\Patient;
use Illuminate\Support\Facades\Crypt;
use App\Models\Archive;
use App\Models\Configuration;
use App\Models\CaseFormat;
use App\Models\ZipDirectory;
use Log;
use Exception;
use Illuminate\Support\Facades\Redirect;
use phpseclib3\Crypt\RSA;  // Updated namespace
use phpseclib3\Net\SFTP;   // Updated namespace

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $zips = Archive::where('hospitalRecordId', 'like', '%' . $search . '%')
            ->paginate(8);
        return view('zips', compact('zips'));
    }

    public function store(Request $request)
    {
        $selectedFiles = $request->input('files');

        if (!is_array($selectedFiles)) {
            return redirect()->back()->withInput()->withErrors(['error' => 'No files selected']);
        }

        $filesToZip = [];
        foreach ($selectedFiles as $fileId) {
            $file = File::findOrFail($fileId);
            $path = $file->file;
            $hospitalRecordId = $file->hospitalRecordId;

            $passwordZip = Patient::where('hospitalRecordId', $hospitalRecordId)->first();
            if (!$passwordZip) {
                return redirect()->back()->withInput()->withErrors(['error' => 'Please set password']);
            }

            $password = decrypt($passwordZip->password);
            $path = str_replace('/storage/', '', $path);
            $absolutePath = storage_path('app/public/' . $path);

            // Decrypt the file content
            $encryptedContent = file_get_contents($absolutePath);
            $decryptedContent = Crypt::decrypt($encryptedContent);

            $filesToZip[] = ['content' => $decryptedContent, 'password' => $password, 'filename' => basename($absolutePath)];
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
        $zipFileName = $zipDirectory . '/' . $hospitalRecordId . '.zip';

        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($filesToZip as $file) {
                $zip->addFromString($file['filename'], $file['content']);
                $zip->setEncryptionName($file['filename'], ZipArchive::EM_AES_256, $file['password']);
            }
            $zip->close();

            Archive::create([
                'zip' => '/storage/zip_files/' . $hospitalRecordId . '.zip',
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

        // Convert public path to storage path
        $absolutePath = public_path($path);
        Log::info('Absolute Path for download: ' . $absolutePath);

        if (!file_exists($absolutePath)) {
            Log::error('File not found: ' . $absolutePath);
            return redirect()->back()->withErrors(['error' => 'File not found.']);
        }

        // Create a download response
        $response = response()->download($absolutePath, basename($path));

        // After download delete the file from storage and database
        $response->deleteFileAfterSend(true);

        // Delete file record from the database
        $file->delete();

        return $response;
    }

    public function downloadPath($id)
    {
        $file = Archive::findOrFail($id);
        $localFilePath = public_path($file->zip);

        if (!File::exists($localFilePath)) {
            return Redirect::back()->withErrors(['error' => 'File not found.']);
        }

        $targetIpAddress = '192.168.1.89';
        $username = 'test';
        $password = '12345';

        try {
            $sftp = new \phpseclib3\Net\SFTP($targetIpAddress, 2222);

            $sftp->sendKEXINITLast(); // Send KEXINIT last for compatibility
            $sftp->setTimeout(0); // Disable timeout
            $sftp->setKeepAlive(5); // Set keep-alive interval
            $sftp->enableDatePreservation(); // Preserve timestamps

            if (!$sftp->login($username, $password)) {
                throw new Exception('SFTP login failed.');
            }

            $remoteDirectory = '/storage/emulated/0';
            $remoteFileName = basename($localFilePath);

            if (!$sftp->put($remoteDirectory . $remoteFileName, $localFilePath, \phpseclib3\Net\SFTP::SOURCE_LOCAL_FILE)) {
                throw new Exception('Failed to send file via SFTP.');
            }

            return Redirect::back()->with('success', 'File sent successfully via SFTP.');
        } catch (\Exception $e) {
            return Redirect::back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateStoragePath(Request $request)
    {
        $request->validate([
            'storage_path' => 'required|string|ip', // Validate IP address format
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
        $storage = ZipDirectory::all()->first();

        // Return the view with both the starting value and the storage path
        return view('setting', compact('storage'));
    }
}
