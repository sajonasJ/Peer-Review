<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'instruction',
        'num_reviews',
        'max_score',
        'due_date',
        'due_time',
        'type',
        'course_id',
    ];
}
