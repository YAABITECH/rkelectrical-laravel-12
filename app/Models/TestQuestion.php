<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class TestQuestion extends Model
{
    protected $table = 'test_questions';

    protected $fillable = [
        'exam_id',
        'section_id',
        'question',
        'options',
        'question_type',
        'answer',
        'answer2',
        'mark',
        'negative_mark',
        'duration',
        'priority',
        'solution',
        'difficulty',
    ];

    protected $casts = [
        'options'       => 'array',
        'mark'          => 'decimal:2',
        'negative_mark' => 'decimal:2',
        'is_public'     => 'boolean',
    ];

    public function getExam()
    {
        return $this->belongsTo(TestExam::class, 'exam_id');
    }
    public function getSection()
    {
        return $this->belongsTo(TestSection::class, 'section_id');
    }
}
