<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtFlashcards extends Model
{
    protected $fillable = ['cbt_technique_id', 'negative_thought', 'positive_reframe'];

    public function technique()
    {
        return $this->belongsTo(CbtTechnique::class);
    }
    use HasFactory;
}
