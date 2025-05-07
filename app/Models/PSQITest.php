<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PSQITest extends Model
{
    use HasFactory;
    protected $table = 'psqi_tests';

    protected $fillable = [
        'patient_id',
        'score',
        'status',
        'sleep_quality',
        'sleep_latency',
        'sleep_duration',
        'sleep_efficiency',
        'sleep_disturbances',
        'use_of_sleep_medication',
        'daytime_dysfunction',
        'answers',
    ];
    protected $hidden = [
        'answers',
    ];
    public function user()
{
    return $this->belongsTo(User::class, 'patient_id');
}

}
