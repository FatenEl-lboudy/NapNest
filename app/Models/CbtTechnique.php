<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtTechnique extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'type',
        'description', 'resource_path',
        'created_at',
        'updated_at',
    ];
    
}
