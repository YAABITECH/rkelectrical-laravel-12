<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['title','content','ulink','mtit','mdes','image','ogimage','post_date','author','status'];

    protected $casts = [
        'post_date' => 'date',
    ];
}
