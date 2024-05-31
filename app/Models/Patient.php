<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use App\Models\CaseFormat;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospitalRecordId',
        'caseNo',
        'fileNo',
        'firstName',
        'middleName',
        'lastName',
        'dateOfBirth',
        'password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            // Set fileNo as before
            $patient->fileNo = $patient->generateFileNo();

            // Generate and set the caseNo based on the CaseFormat
            $patient->caseNo = CaseFormat::getNextCaseNo();
        });

        static::created(function ($patient) {
            // Increment the auto-number in CaseFormat after patient record is created
            CaseFormat::incrementAutoNumber();
        });
    }

    public function generateFileNo()
    {
        $this->ensureStartingValueExists();

        $lastFile = static::orderBy('fileNo', 'desc')->first();
        $startingValue = Configuration::where('key', 'filenumber.starting_value')->value('value');

        $nextFileNo = intval($startingValue); // Default to starting value

        if ($lastFile && $lastFile->fileNo) {
            $lastFileNo = intval(substr($lastFile->fileNo, 4));
            $nextFileNo = $lastFileNo >= $startingValue ? $lastFileNo + 1 : $startingValue;
        }

        return 'FILE' . str_pad($nextFileNo, 3, '0', STR_PAD_LEFT);
    }

    protected function ensureStartingValueExists()
    {
        if (!Configuration::where('key', 'filenumber.starting_value')->exists()) {
            Configuration::create([
                'key' => 'filenumber.starting_value',
                'value' => '1'
            ]);
        }
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class, 'hospitalRecordId');
    }

    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class, 'hospitalRecordId');
    }
}
