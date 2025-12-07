<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeforePay extends Model
{
    use HasFactory;
    protected $fillable =['user_id', 'course_id', 'coursepack_id', 'test_id', 'amount'];
}
