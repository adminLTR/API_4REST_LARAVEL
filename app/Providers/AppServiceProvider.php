<?php

namespace App\Providers;

use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Services\ExternalPaymentService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar repositorios
        $this->app->singleton(OrderRepository::class);
        $this->app->singleton(PaymentRepository::class);

        // Registrar servicios
        $this->app->singleton(ExternalPaymentService::class);
        $this->app->singleton(OrderService::class);
        $this->app->singleton(PaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
