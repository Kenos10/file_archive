<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\File;
use App\Models\PasswordZip;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $patients = Patient::where('firstName', 'like', '%' . $search . '%')
            ->orWhere('middleName', 'like', '%' . $search . '%')
            ->orWhere('lastName', 'like', '%' . $search . '%')
            ->orWhere('hospitalRecordId', 'like', '%' . $search . '%')
            ->orWhere('caseNo', 'like', '%' . $search . '%')
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('patientlist', compact('patients'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'hospitalRecordId' => 'required|unique:patients',
            'caseNo' => 'required|unique:patients',
            'firstName' => 'required',
            'middleName' => 'required',
            'lastName' => 'required',
            'dateOfBirth' => 'required',
        ]);

        // Create the patient record
        $patient = Patient::create($validatedData);

        // Generate a random password
        $password = Str::random(10); // Adjust the length as needed
        $encryptedPassword = encrypt($password); // Encrypt the password

        // Create a new PasswordZip record associated with the patient
        PasswordZip::create([
            'hospitalRecordId' => $patient->hospitalRecordId,
            'password' => $encryptedPassword,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Patient created successfully.');
    }

    public function show($hospitalRecordId)
    {
        $patient = Patient::where('hospitalRecordId', $hospitalRecordId)->firstOrFail();
        $files = File::where('hospitalRecordId', $hospitalRecordId)->get();
        $zipfile = Archive::where('hospitalRecordId', $hospitalRecordId)->get();

        $passwordRecord = PasswordZip::where('hospitalRecordId', $hospitalRecordId)->first();
        if ($passwordRecord) {
            $decryptedPassword = decrypt($passwordRecord->password);
        } else {
            $decryptedPassword = null;
        }

        return view('viewpatient', compact('patient', 'files', 'zipfile', 'decryptedPassword'));
    }

    public function view($id)
    {
        $file = File::where("id", $id)->firstOrFail();
        $path = $file->file;

        // Remove the '/storage/' from the beginning of the path
        $path = str_replace('/storage/', '', $path);

        // Get the file contents
        $absolutePath = storage_path('app/public/' . $path);
        $encryptedContents = file_get_contents($absolutePath);
        $decryptedContents = decrypt($encryptedContents);

        // Determine the MIME type
        $mimeType = mime_content_type($absolutePath);

        // Return the file contents as a response with the appropriate headers
        return response($decryptedContents)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }

    // public function edit(Patient $patient)
    // {
    //     return view('patients.edit', compact('patient'));
    // }

    // public function create()
    // {
    //     return view('patients.create');
    // }

    // public function update(Request $request, Patient $patient)
    // {
    //     $validatedData = $request->validate([
    //         'hospitalRecordId' => 'required',
    //         'caseNo' => 'required',
    //         'firstName' => 'required',
    //         'middleName' => 'required',
    //         'lastName' => 'required',
    //         'dateOfBirth' => 'required',
    //     ]);

    //     $patient->update($validatedData);
    //     return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    // }

    // public function destroy(Patient $patient)
    // {
    //     $patient->delete();
    //     return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    // }
}
