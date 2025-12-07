<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'topic_id',
        'subtopic_id',
        'attended',
        'answered',
        'right_answer',
        'right_answer_question_ids',
        'wrong_answer',
        'wrong_answer_question_ids',
        'duration',
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    public function scopeUsersForTopic($query, $topicId)
    {
        return $query->where('topic_id', $topicId)->with('user');
    }

    public function scopeUsersForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId)->with('user');
    }

    public function scopeUsersForSubtopic($query, $subtopicId)
    {
        return $query->where('subtopic_id', $subtopicId)->with('user');
    }
}
