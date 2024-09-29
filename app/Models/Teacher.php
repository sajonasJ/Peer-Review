<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Authenticatable
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = [
        'name',
        'email',
        'snumber',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Define the relationship to courses
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'teacher_courses',     // Pivot table name
            'teacher_id',          // Foreign key on pivot table for the Teacher model
            'course_code',         // Foreign key on pivot table for the Course model
            'id',                  // Local key on Teacher model
            'course_code'          // Local key on Course model
        );
    }
}
