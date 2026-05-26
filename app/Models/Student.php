<?php

namespace App\Models;

use App\Models\Traits\UserIsolated;
use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasFactory;

    use UserIsolated;

    protected $fillable = [
        'name',
        'email',
        'user_id',
        'course_id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
