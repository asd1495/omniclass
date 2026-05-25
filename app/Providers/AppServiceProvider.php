<?php

namespace App\Providers;

use App\Contexts\School\Domain\Repository\StudentRepository;
use App\Contexts\School\Infrastructure\Persistence\EloquentStudentRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StudentRepository::class, EloquentStudentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
