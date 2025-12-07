<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeSubtopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'topic_id',
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

    public function practiceTopic()
    {
        return $this->belongsTo(PracticeTopic::class, 'topic_id');
    }

    public function practiceQuestions()
    {
        return $this->hasMany(PracticeQuestion::class, 'subtopic_id');
    }

}
