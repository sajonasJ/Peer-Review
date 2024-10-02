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
    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Student::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(Student::class, 'reviewee_id');
    }
}
