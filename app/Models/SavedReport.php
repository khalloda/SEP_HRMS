<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedReport extends Model
{
    protected $fillable = [
        'user_id','name','report_key','params','format','schedule','recipients',
    ];

    protected $casts = [
        'params' => 'array',
    ];
}

