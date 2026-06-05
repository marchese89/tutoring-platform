<?php

namespace App\Providers;

use App\Payments\CashierPaymentGateway;
use App\Payments\PaymentGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGateway::class, CashierPaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
