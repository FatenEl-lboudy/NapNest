<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyPath extends Model
{
    protected $table = 'my_paths';
    protected $fillable = [
        'patient_id',
        'title',
        'instructions',
        'day_index',
        'is_completed',
        'scheduled_for'
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
