<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'photo',
        'preview',
        'description',
        'fees',
        'coursedur',
        'classdur',
        'metatitle',
        'metadesc',
        'url',
        'course_pack',
    ];
}
