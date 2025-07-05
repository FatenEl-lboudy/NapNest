<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanStep extends Model
{
    use HasFactory;
    public function myPlan()
{
    return $this->belongsTo(MyPlan::class);
}

}
