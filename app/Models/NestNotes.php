<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NestNotes extends Model
{
    protected $table = 'nest_notes';
    protected $fillable = [
        'title',
        'description',
        'content',
        'tagline',
        'is_featured',
        'section',
        'slug'
    ];
    use HasFactory;
}
