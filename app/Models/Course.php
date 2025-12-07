<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title','photo','preview','description','fees','coursedur','classdur','metatitle','metadesc','url','course_pack',
    ];
    public function coursePaid()
    {
        return $this->hasMany(CoursePaid::class, 'course_id');
    }
    public function getChapters()
    {
        return $this->hasMany(CourseVideo::class, 'course_id');
    }
}
