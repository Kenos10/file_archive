<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CaseFormat extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix',
        'prefix_value',
        'prefix_date',
        'prefix_year_format',
        'prefix_year_only',
        'prefix_month_only',
        'prefix_day_only',
        'suffix',
        'suffix_value',
        'suffix_date',
        'suffix_year_format',
        'suffix_year_only',
        'suffix_month_only',
        'suffix_day_only',
        'auto_number',
        'starter_number',
        'include_hyphens'
    ];

    public static function incrementAutoNumber()
    {
        $caseFormat = self::first(); // Assuming there is only one record
        if ($caseFormat && $caseFormat->auto_number) {
            $caseFormat->starter_number += 1;
            $caseFormat->save();
        }
    }

    public static function getNextCaseNo()
    {
        $caseFormat = self::first(); // Assuming there is only one record
        if ($caseFormat) {
            $nextNumber = str_pad($caseFormat->starter_number, 3, '0', STR_PAD_LEFT);

            // Constructing case number
            $caseNo = '';

            // Constructing case number with prefix values
            if ($caseFormat->prefix) {
                $caseNo .= $caseFormat->prefix_value ?? '';
            }
            if ($caseFormat->prefix_date) {
                $date = Carbon::now();
                if ($caseFormat->prefix_year_only) {
                    $caseNo .= $date->format($caseFormat->prefix_year_format === 'short' ? 'y' : 'Y');
                }
                if ($caseFormat->prefix_month_only) {
                    $caseNo .= $date->format('m');
                }
                if ($caseFormat->prefix_day_only) {
                    $caseNo .= $date->format('d');
                }
            }

            // Adding the auto-increment number
            $caseNo .= $nextNumber;

            // Constructing case number with suffix values
            if ($caseFormat->suffix) {
                $caseNo .= $caseFormat->suffix_value ?? '';
            }
            if ($caseFormat->suffix_date) {
                $date = Carbon::now();
                if ($caseFormat->suffix_year_only) {
                    $caseNo .= $date->format($caseFormat->suffix_year_format === 'short' ? 'y' : 'Y');
                }
                if ($caseFormat->suffix_month_only) {
                    $caseNo .= $date->format('m');
                }
                if ($caseFormat->suffix_day_only) {
                    $caseNo .= $date->format('d');
                }
            }

            return $caseNo;
        }
        return null;
    }
}
