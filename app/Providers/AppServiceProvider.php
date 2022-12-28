<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\Specializations\SpecializationService;
use App\Services\Specializations\SpecializationInterface;
use App\Services\Users\UserService;
use App\Services\Users\UserServiceInterface;
use App\Services\Doctors\DoctorService;
use App\Services\Doctors\DoctorServiceInterface;

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
        $this->app->bind(SpecializationInterface::class, SpecializationService::class);
        $this->app->bind(DoctorServiceInterface::class, DoctorService::class);
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
