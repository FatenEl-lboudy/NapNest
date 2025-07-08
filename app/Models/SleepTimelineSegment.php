<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SleepTimelineSegment extends Model
{
    protected $fillable = [
        'sleep_metric_id',
        'state',
        'start_time',
        'end_time',
        'duration_min'
    ];

    public function metric()
    {
        return $this->belongsTo(SleepMetric::class, 'sleep_metric_id');
    }
    use HasFactory;
}
