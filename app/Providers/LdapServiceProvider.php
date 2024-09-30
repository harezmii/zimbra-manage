<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use LDAP\Connection;

class LdapServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('ldap', function ($app) {
            $config = [
                'hosts' => [env('LDAP_HOST')],
                'base_dn' => env('LDAP_BASE_DN'),
                'username' => env('LDAP_USER'),
                'password' => env('LDAP_PASSWORD'),
            ];

            return new Connection($config);
        });
    }

    public function boot()
    {

    }

}
