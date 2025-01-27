<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'name',
    ];

    // Define the relationship to students
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'student_courses',
            'course_code',
            'student_id',
            'course_code',
            'id'
        );
    }

    // Define the relationship to teachers
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            Teacher::class,
            'teacher_courses',
            'course_code',
            'teacher_id',
            'course_code',
            'id'
        );
    }

    // Define the relationship to assessments
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
