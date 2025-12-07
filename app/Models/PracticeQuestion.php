<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'topic_id',
        'subtopic_id',
        'question',
        'option1',
        'option2',
        'option3',
        'option4',
        'question_type',
        'answer1',
        'answer2',
        'solution',
        'mark',
        'duration',
        'priority',
        'status',
    ];

    public function practiceSubject()
    {
        return $this->belongsTo(PracticeSubject::class, 'subject_id');
    }

    public function practiceTopic()
    {
        return $this->belongsTo(PracticeTopic::class, 'topic_id');
    }

    public function practiceSubtopic()
    {
        return $this->belongsTo(PracticeSubtopic::class, 'subtopic_id');
    }
}
