<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    protected $fillable = [
        'share_token',
        'original_name',
        'stored_name',
        'disk',
        'path',
        'mime_type',
        'size',
        'duration_seconds',
        'google_drive_file_id',
        'google_drive_web_view_link',
    ];
}
