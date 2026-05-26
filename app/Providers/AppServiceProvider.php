<?php

namespace App\Providers;

use App\Contexts\School\Domain\Repository\AttendanceRepository;
use App\Contexts\School\Domain\Repository\StudentRepository;
use App\Contexts\School\Domain\Repository\TeacherRepository;
use App\Contexts\School\Infrastructure\Persistence\EloquentAttendanceRepository;
use App\Contexts\School\Infrastructure\Persistence\EloquentStudentRepository;
use App\Contexts\School\Infrastructure\Persistence\EloquentTeacherRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StudentRepository::class, EloquentStudentRepository::class);
        $this->app->bind(TeacherRepository::class, EloquentTeacherRepository::class);
        $this->app->bind(AttendanceRepository::class, EloquentAttendanceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
