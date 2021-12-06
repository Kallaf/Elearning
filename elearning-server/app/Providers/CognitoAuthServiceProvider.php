<?php
namespace App\Providers;

use App\Auth\CognitoGuard;
use App\Cognito\CognitoClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

class CognitoAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(CognitoClient::class, function (Application $app) {
            $config = [
                'region'      => env('AWS_COGNITO_REGION'),
                'version'     => env('AWS_COGNITO_VERSION')
            ];

            return new CognitoClient(
                new CognitoIdentityProviderClient($config),
                env('AWS_COGNITO_CLIENT_ID'),
                env('AWS_COGNITO_CLIENT_SECRET'),
                env('AWS_COGNITO_USER_POOL_ID')
            );
        });
  
    }
}