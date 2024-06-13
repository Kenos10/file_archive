<?php

use App\Models\FtpSetting;
use Illuminate\Support\Facades\Schema;

$ftpSetting = null;

if (Schema::hasTable('ftp_settings')) {
    $ftpSetting = FtpSetting::first();
}

return [
    'host' => $ftpSetting->ftp_host ?? env('FTP_HOST', 'default_host'),
    'username' => $ftpSetting->ftp_username ?? env('FTP_USERNAME', 'default_username'),
    'password' => $ftpSetting->ftp_password ?? env('FTP_PASSWORD', 'default_password'),
    'port' => $ftpSetting->ftp_port ?? env('FTP_PORT', 2222),
];
