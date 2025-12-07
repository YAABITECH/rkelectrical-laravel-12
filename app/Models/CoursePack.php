<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePack extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','subname','fees',
    ];
    public function assignments()
    {
        return $this->hasMany(CoursePaid::class, 'user_id');
    }
    public function getCourses()
    {
        return $this->hasMany(Course::class, 'course_pack');
    }
}
