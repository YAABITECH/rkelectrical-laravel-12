<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    protected $fillable = [
        'exam_id', 'question_id', 'user_id',
        'answer', 'status'
    ];
}
