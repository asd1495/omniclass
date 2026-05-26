<?php

namespace App\Models;

use App\Models\Traits\UserIsolated;
use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    use UserIsolated;

    protected $fillable = ['name', 'user_id'];
}
