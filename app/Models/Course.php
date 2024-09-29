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
            'student_courses',      // Pivot table name
            'course_code',          // Foreign key on the pivot table for the Course model
            'student_id',           // Foreign key on the pivot table for the Student model
            'course_code',          // Local key on Course model (course_code)
            'id'                    // Related key on Student model (primary key)
        );
    }

    // Define the relationship to teachers
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(
            Teacher::class,
            'teacher_courses',     // Pivot table name
            'course_code',         // Foreign key on the pivot table for the Course model
            'teacher_id',          // Foreign key on the pivot table for the Teacher model
            'course_code',         // Local key on the Course model
            'id'                   // Related key on Teacher model (primary key)
        );
    }

    // Define the relationship to assessments
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
}
