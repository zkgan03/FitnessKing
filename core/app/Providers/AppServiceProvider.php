<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Accounts\AccountService;
use App\Services\Accounts\CaptchaService;
use App\Services\Chatbot\ChatbotService;
use App\Services\Payment\PaymentService;
use App\Services\Enrollment\EnrollmentService;
use App\Services\ClassPackage\ClassPackageService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Moved it here since only using one service
        $this->app->singleton('accounts', function ($app) {
            return new AccountService();
        });

        // DI for services
        $this->app->singleton(PaymentService::class);
        $this->app->singleton(EnrollmentService::class);
        $this->app->singleton(ChatbotService::class);
        $this->app->singleton(ClassPackageService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
