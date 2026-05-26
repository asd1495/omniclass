<?php

namespace App\Models;

use App\Models\Traits\UserIsolated;
use Database\Factories\AttendanceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /** @use HasFactory<AttendanceFactory> */
    use HasFactory;

    use UserIsolated;

    protected $fillable = [
        'student_id',
        'date',
        'status',
        'user_id',
    ];

    protected $casts = [
        'date' => 'immutable_date',
    ];
}
