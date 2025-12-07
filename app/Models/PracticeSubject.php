<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'priority',
        'status',
        'launch_date',
        'mtit',
        'mdes',
        'ulink',
        'image',
        'ogimage',
        'launch_datetime',
    ];

    public function practiceTopics()
    {
        return $this->hasMany(PracticeTopic::class, 'subject_id');
    }
    public function practiceSubtopics()
    {
        return $this->hasMany(PracticeSubtopic::class, 'subject_id');
    }
    public function practiceQuestions()
    {
        return $this->hasMany(PracticeQuestion::class, 'subject_id');
    }

}
