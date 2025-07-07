<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SleepAlarm extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'wake_time',
        'vibration',
        'sound_label',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
