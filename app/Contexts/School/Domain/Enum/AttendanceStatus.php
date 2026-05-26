<?php

declare(strict_types=1);

namespace App\Contexts\School\Domain\Enum;

enum AttendanceStatus: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LATE = 'late';
    case EXCUSED = 'excused';
}
