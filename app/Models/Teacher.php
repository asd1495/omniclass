<?php

namespace App\Models;

use App\Models\Traits\UserIsolated;
use Database\Factories\TeacherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /** @use HasFactory<TeacherFactory> */
    use HasFactory;

    use UserIsolated;

    protected $fillable = [
        'name',
        'email',
        'user_id',
    ];
}
