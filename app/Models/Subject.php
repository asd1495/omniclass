<?php

namespace App\Models;

use App\Models\Traits\UserIsolated;
use Database\Factories\SubjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /** @use HasFactory<SubjectFactory> */
    use HasFactory;

    use UserIsolated;

    protected $fillable = ['name', 'user_id'];
}
