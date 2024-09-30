<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Microsoft\Graph\GraphServiceClient;
use Microsoft\Kiota\Authentication\Oauth\ClientCredentialContext;

class GraphServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(GraphServiceClient::class, function ($app) {
            $tenantId = env('GRAPH_TENANT_ID');
            $clientId = env('GRAPH_CLIENT_ID');
            $clientSecret = env('GRAPH_CLIENT_SECRET');

            $tokenRequestContext = new ClientCredentialContext(
                $tenantId,
                $clientId,
                $clientSecret
            );

            // Optional: Define scopes if needed
            $scopes = ['.default'];

            return new GraphServiceClient($tokenRequestContext, $scopes);
        });
    }

    public function boot()
    {
        //
    }
}
