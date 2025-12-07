<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_id',
        'priority',
        'status',
        'mtit',
        'mdes',
        'ulink',
        'image',
        'ogimage',
        'launch_datetime',
    ];

    public function practiceSubject()
    {
        return $this->belongsTo(PracticeSubject::class, 'subject_id');
    }

    public function practiceSubtopics()
    {
        return $this->hasMany(PracticeSubtopic::class, 'topic_id');
    }

    public function practiceQuestions()
    {
        return $this->hasMany(PracticeQuestion::class, 'topic_id');
    }
}
