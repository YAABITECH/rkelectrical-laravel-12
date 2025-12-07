<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AdminAuthenticatable;

class Admin extends AdminAuthenticatable
{
    use HasFactory;
    protected $fillable = [
        'username','password','name','admin_hash',
    ];
}
