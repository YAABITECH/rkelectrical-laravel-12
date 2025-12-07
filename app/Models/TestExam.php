<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestExam extends Model
{
    protected $table = 'test_exams';

    protected $fillable = [
        'series_id',
        'name',
        'tagline',
        'description',
        'instruction',
        'url_slug',
        'ulink',
        'total_questions',
        'marks',
        'duration',
        'attempt_limit',
        'difficulty',
        'priority',
        'status',
        'is_demo',
        'has_negative_marks',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'is_demo'           => 'boolean',
        'has_negative_marks'=> 'boolean',
        'start_at'          => 'datetime',
        'end_at'            => 'datetime',
    ];

    public function getSeries()
    {
        return $this->belongsTo(TestSeries::class, 'series_id');
    }

    public function getSections()
    {
        return $this->hasMany(TestSection::class, 'test_id');
    }

    public function getQuestions()
    {
        return $this->hasMany(TestQuestion::class, 'exam_id');
    }
}
