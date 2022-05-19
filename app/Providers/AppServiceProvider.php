<?php

namespace App\Providers;

use App\Converters\CbrService;
use App\Converters\ConverterContract;
use App\Converters\CurrConvService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ConverterContract::class, function($app) {
            $client = new Client();
            return new CbrService($client);
            //return new CurrConvService($client, config('services.currconv.key')); // Uncomment this to use another service
        });
    }
}
