<?php

namespace App\Models;

use App\Models\Traits\UserIsolated;
use Database\Factories\SubjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    /** @use HasFactory<SubjectFactory> */
    use HasFactory;

    use UserIsolated;

    protected $fillable = ['name', 'user_id', 'course_id'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
