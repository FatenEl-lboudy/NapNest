<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SleepTune extends Model
{
    protected $table = 'sleep_tunes';

    protected $fillable = [
        'title',
        'description',
        'file_url',
        'is_featured',
    ];
    use HasFactory;
}
