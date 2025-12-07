<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestSection extends Model
{
    protected $table = 'test_sections';

    protected $fillable = [
        'test_id',
        'name',
        'priority',
    ];

    public function getExam()
    {
        return $this->belongsTo(TestExam::class, 'test_id');
    }

    public function getQuestions()
    {
        return $this->hasMany(TestQuestion::class, 'section_id');
    }
}
