<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Configuration;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospitalRecordId',
        'file',
        'fileNo'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            $file->fileNo = $file->generateFileNo();
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

    public function save(array $options = [])
    {
        DB::beginTransaction();

        try {
            $saved = parent::save($options);

            if ($saved) {
                $config = Configuration::where('key', 'filenumber.starting_value')->first();
                if ($config) {
                    $nextFileNo = intval(substr($this->fileNo, 4));
                    $config->value = $nextFileNo;
                    $config->save();
                }

                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return false;
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'hospitalRecordId');
    }

}
