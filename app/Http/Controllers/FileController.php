<?php

namespace App\Http\Controllers;

use App\Models\ArchiveDirectory;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Configuration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Config;
use Artisan;

class FileController extends Controller
{
    // Display a listing of the files.
    public function index(Request $request)
    {
        $search = $request->get('search');
        $files = File::where('hospitalRecordId', 'like', '%' . $search . '%')
            ->orWhere('fileNo', 'like', '%' . $search . '%')
            ->orderByDesc('fileNo')
            ->paginate(10);
        return view('files', compact('files'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'hospitalRecordId' => 'required',
            'file' => 'required|mimes:jpeg,png,png,csv,txt,xlsx,xls,ppt,pptx,doc,docx,pdf|max:5048',
        ]);

        // Generate a unique file number
        $fileModel = new File;
        $fileNo = $fileModel->generateFileNo();

        if ($request->file()) {
            // Get the original file name
            $originalFileName = $request->file->getClientOriginalName();
            // Create a new file name with the file number prefixed
            $fileName = $fileNo . '_' . $originalFileName;
            // Store the file in the 'uploads' directory under 'public' disk
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');

            // Get the absolute path to the stored file
            $absolutePath = storage_path('app/public/' . $filePath);
            // Read the contents of the file
            $contents = file_get_contents($absolutePath);
            // Encrypt the file contents
            $encryptedContents = encrypt($contents);
            // Overwrite the file with the encrypted contents
            file_put_contents($absolutePath, $encryptedContents);

            // Save file information to the database
            $fileModel->file = '/storage/' . $filePath;
            $fileModel->hospitalRecordId = $request->input('hospitalRecordId');
            $fileModel->fileNo = $fileNo;
            $fileModel->save();

            // Return a success response
            return back()->with('success', 'File has been uploaded.')->with('file', $fileName);
        }

        // Return an error response if the file upload fails
        return redirect()->route('viewpatientlist')->with('error', 'File upload failed.');
    }

    // public function create()
    // {
    //     return view('files.create');
    // }

    // public function show(File $file)
    // {
    //     return view('files.show', compact('file'));
    // }

    // public function edit(File $file)
    // {
    //     return view('files.edit', compact('file'));
    // }

    // public function update(Request $request, File $file)
    // {
    //     $request->validate([
    //         'hospitalRecordId' => 'required',
    //         'file' => 'required',
    //     ]);

    //     $file->update($request->all());

    //     return redirect()->route('files.index')
    //         ->with('success', 'File updated successfully');
    // }

    // public function destroy(File $file)
    // {
    //     $file->delete();

    //     return redirect()->route('files.index')
    //         ->with('success', 'File deleted successfully');
    // }
}
