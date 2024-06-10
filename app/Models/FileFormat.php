<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FileFormat extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix',
        'prefix_value',
        'prefix_year_format',
        'prefix_year_only',
        'prefix_month_only',
        'prefix_day_only',
        'suffix',
        'suffix_value',
        'suffix_year_format',
        'suffix_year_only',
        'suffix_month_only',
        'suffix_day_only',
        'auto_number',
        'starter_number',
        'include_hyphens',
    ];

    public static function getNextFileNo()
    {
        $format = self::first();
        $date = Carbon::now();

        $prefix = self::formatDateComponent($format, 'prefix', $date);
        $suffix = self::formatDateComponent($format, 'suffix', $date);
        $autoNumber = self::getAutoNumber($format);

        $fileNo = $prefix . ($format->include_hyphens ? '-' : '') . $autoNumber . ($format->include_hyphens ? '-' : '') . $suffix;

        return $fileNo;
    }

    private static function formatDateComponent($format, $type, $date)
    {
        $component = '';

        if ($format->{$type} === 'date') {
            $componentParts = [];

            if ($format->{$type . '_year_only'}) {
                $componentParts[] = $date->format($format->{$type . '_year_format'} === 'short' ? 'y' : 'Y');
            }

            if ($format->{$type . '_month_only'}) {
                $componentParts[] = $date->format('m');
            }

            if ($format->{$type . '_day_only'}) {
                $componentParts[] = $date->format('d');
            }

            if (empty($componentParts)) {
                $componentParts[] = $date->format('Y-m-d');
            }

            $component = implode($format->include_hyphens ? '-' : '', $componentParts);
        } elseif ($format->{$type} === 'string') {
            $component = $format->{$type . '_value'};
        }

        return $component;
    }

    private static function getAutoNumber($format)
    {
        $autoNumber = '';

        if ($format->auto_number) {
            $currentNumber = $format->starter_number ?? 1;
            $autoNumber = str_pad($currentNumber, 3, '0', STR_PAD_LEFT);
        }

        return $autoNumber;
    }

    public static function incrementAutoNumber()
    {
        $format = self::first();
        if ($format->auto_number) {
            $format->starter_number = ($format->starter_number ?? 1) + 1;
            $format->save();
        }
    }
}
