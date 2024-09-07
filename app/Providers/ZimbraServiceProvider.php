<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Zimbra\Admin\AdminApi;

class ZimbraServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AdminApi::class, function ($app) {
            // Burada Zimbra API bağlantısını yapıyoruz
            $username = config('services.zimbra.username');
            $password = config('services.zimbra.password');
            $url      = config('services.zimbra.url');
            $api = new AdminApi($url);
            $api->auth($username, $password);
            return $api;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
