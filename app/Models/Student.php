<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'email',
        'snumber',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'student_courses',      // Pivot table name
            'student_id',           // Foreign key on pivot table for the Student model
            'course_code',          // Foreign key on pivot table for the Course model
            'id',                   // Local key on Student model (primary key)
            'course_code'           // Related key on Course model
        );
    }
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }
    
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }
    
}
