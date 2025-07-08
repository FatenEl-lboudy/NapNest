<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtTechnique extends Model
{
    use HasFactory;
    protected $table = 'cbt_techniques';
    protected $fillable = [
        'title',
        'type',
        'description', 'benefits', 'resource_path', 'is_featured'
    ];
    
}
