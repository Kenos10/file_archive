<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\File;
use Illuminate\Support\Str;
use App\Models\CaseFormat;
use App\Models\FileFormat;

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
            ->orWhere('fileNo', 'like', '%' . $search . '%')
            ->orderByDesc('created_at')
            ->paginate(8);
        return view('patientlist', compact('patients'));
    }

    public function show($hospitalRecordId)
    {
        $patient = Patient::where('hospitalRecordId', $hospitalRecordId)->firstOrFail();
        $files = File::where('hospitalRecordId', $hospitalRecordId)->get();
        $zipfile = Archive::where('hospitalRecordId', $hospitalRecordId)->get();

        if ($patient->password) {
            $decryptedPassword = decrypt($patient->password);
        } else {
            $decryptedPassword = null;
        }

        return view('viewpatient', compact('patient', 'files', 'zipfile', 'decryptedPassword'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'hospitalRecordId' => 'required|numeric|digits:8|unique:patients',
            'firstName' => 'required|string',
            'middleName' => 'required|string',
            'lastName' => 'required|string',
            'dateOfBirth' => 'required|date',
        ]);

        // Generate a random password
        $password = Str::random(8);
        $encryptedPassword = encrypt($password);

        // Get the next case number
        $caseNo = CaseFormat::getNextCaseNo();

        // Check if the case number already exists
        $existingPatient = Patient::where('caseNo', $caseNo)->first();

        if ($existingPatient) {
            // Retrieve the existing file number associated with the case number
            $fileNo = $existingPatient->fileNo;
        } else {
            // Generate a new file number if the case number doesn't exist
            $fileNo = FileFormat::getNextFileNo();

            // Check if the generated file number already exists
            while (Patient::where('fileNo', $fileNo)->exists()) {
                $fileNo = FileFormat::getNextFileNo(); // Generate a new file number
            }
        }

        // Create a new Patient record
        $patient = new Patient();
        $patient->fill([
            'hospitalRecordId' => $validatedData['hospitalRecordId'],
            'caseNo' => $caseNo,
            'fileNo' => $fileNo,
            'firstName' => $validatedData['firstName'],
            'middleName' => $validatedData['middleName'],
            'lastName' => $validatedData['lastName'],
            'dateOfBirth' => $validatedData['dateOfBirth'],
            'password' => $encryptedPassword,
        ]);
        $patient->save();

        // Increment the starter number after inserting the patient record
        CaseFormat::incrementAutoNumber();

        // Update the starter number in file_format
        $fileFormat = FileFormat::first();
        $fileFormat->starter_number = CaseFormat::first()->starter_number;
        $fileFormat->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Patient created successfully.');
    }

    private function getNextAvailableStarterNumber($currentStarterNumber)
    {
        // Check if the current starter number already exists in the database
        $existingPatient = Patient::where('caseNo', 'LIKE', '%' . $currentStarterNumber . '%')->first();

        // If the current starter number exists, increment it until a unique one is found
        if ($existingPatient) {
            $nextStarterNumber = (int) $currentStarterNumber + 1; // Cast $currentStarterNumber to integer
            return $this->getNextAvailableStarterNumber($nextStarterNumber);
        }

        return $currentStarterNumber;
    }

    private function generateCaseNumber($caseFormat, $starterNumber)
    {
        $caseNo = '';

        // Construct the case number based on the case format configuration
        if ($caseFormat->prefix) {
            // Handle prefix
            if ($caseFormat->prefix === 'string') {
                $caseNo .= $caseFormat->prefix_value;
            } elseif ($caseFormat->prefix === 'date') {
                // Handle date prefix
                $caseNo .= $this->formatDate($caseFormat->prefix_date, $caseFormat, 'prefix');
            }
        }

        // Handle auto number
        if ($caseFormat->auto_number) {
            // Incremental logic for auto number
            $autoNumber = str_pad($starterNumber, 3, '0', STR_PAD_LEFT);
            $caseNo .= ($caseFormat->include_hyphens ? '-' : '') . $autoNumber;
        }

        if ($caseFormat->suffix) {
            // Handle suffix
            if ($caseFormat->suffix === 'string') {
                $caseNo .= ($caseFormat->include_hyphens ? '-' : '') . $caseFormat->suffix_value;
            } elseif ($caseFormat->suffix === 'date') {
                // Handle date suffix
                $caseNo .= ($caseFormat->include_hyphens ? '-' : '') . $this->formatDate($caseFormat->suffix_date, $caseFormat, 'suffix');
            }
        }

        return $caseNo;
    }

    private function formatDate($date, $caseFormat, $type)
    {
        $formattedDate = '';
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        if ($caseFormat->{$type . '_year_only'}) {
            $formattedDate .= ($caseFormat->{$type . '_year_format'} === 'short' ? substr($year, -2) : $year);
        }

        if ($caseFormat->{$type . '_month_only'}) {
            $formattedDate .= ($formattedDate && $caseFormat->include_hyphens ? '-' : '') . $month;
        }

        if ($caseFormat->{$type . '_day_only'}) {
            $formattedDate .= ($formattedDate && $caseFormat->include_hyphens ? '-' : '') . $day;
        }

        return $formattedDate;
    }
}
