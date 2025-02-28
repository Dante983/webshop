<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('paypal.client', function ($app) {
            $mode = config('services.paypal.mode', 'sandbox');
            $clientId = config('services.paypal.client_id');
            $clientSecret = config('services.paypal.client_secret');

            if ($mode === 'sandbox') {
                $environment = new SandboxEnvironment($clientId, $clientSecret);
            } else {
                $environment = new ProductionEnvironment($clientId, $clientSecret);
            }

            return new PayPalHttpClient($environment);
        });
    }
} 