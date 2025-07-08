<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SleepMetric extends Model
{
    protected $fillable = [
        'patient_id',
        'sleep_date',
        'time_in_bed_min',
        'sleep_onset_latency_min',
        'total_sleep_time_min',
        'wake_after_sleep_onset_min',
        'sleep_efficiency_pct',
        'number_of_awakenings',
        'rem_latency_min'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timelineSegments()
    {
        return $this->hasMany(SleepTimelineSegment::class);
    }
    use HasFactory;
}
