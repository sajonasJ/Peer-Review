<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_text',
        'rating',
        'reviewer_id',
        'reviewee_id',
        'assessment_id',
    ];
}
