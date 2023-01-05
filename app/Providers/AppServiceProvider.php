<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Specializations\SpecializationService;
use App\Services\Specializations\SpecializationServiceInterface;
use App\Services\Users\UserService;
use App\Services\Users\UserServiceInterface;
use App\Services\Doctors\DoctorService;
use App\Services\Doctors\DoctorServiceInterface;
use App\Services\Shifts\ShiftService;
use App\Services\Shifts\ShiftServiceInterface;
use App\Services\Mail\MailService;
use App\Services\Mail\MailServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(SpecializationServiceInterface::class, SpecializationService::class);
        $this->app->bind(DoctorServiceInterface::class, DoctorService::class);
        $this->app->bind(ShiftServiceInterface::class, ShiftService::class);
        $this->app->bind(MailServiceInterface::class, MailService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
