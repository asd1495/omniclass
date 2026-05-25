<?php

namespace App\Models;

use App\Models\Traits\UserIsolated;
use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasFactory, UserIsolated;

    protected $fillable = [
        'name',
        'email',
        'user_id',
    ];
}
