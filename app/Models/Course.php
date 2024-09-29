<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'name',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'student_courses',      // Pivot table name
            'course_code',          // Foreign key on pivot table for the Course model
            'student_id',           // Foreign key on pivot table for the Student model
            'course_code',          // Local key on Course model (course_code is not a primary key, but you can still use it here)
            'id'                    // Related key on Student model (primary key)
        );
    }
}
