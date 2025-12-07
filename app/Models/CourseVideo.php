<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    use HasFactory;
    protected $fillable = [
        'topic','video','course_id','description','demo','priority'
    ];

    public function getCourse()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
