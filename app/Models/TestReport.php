<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestReport extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'exam_id',
        'done',
        'start_at',
        'end_at',
        'attempt',
        'total_questions',
        'total_duration',
        'total_marks',
        'attended',
        'duration',
        'positive_marks',
        'negative_marks',
        'marks',
        'meta_data',
    ];

    protected $casts = [
        'done' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'meta_data' => 'array',
    ];

    public function getExam()
    {
        return $this->belongsTo(TestExam::class, 'exam_id');
    }
    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
