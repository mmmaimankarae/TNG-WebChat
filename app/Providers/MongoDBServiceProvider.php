<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MongoDB\Client as MongoClient;

class MongoDBServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(MongoClient::class, function ($app) {
            $config = config('mongodb.default');
            $uri = sprintf('mongodb://%s:%s', $config['host'], $config['port']);
            return new MongoClient($uri, [
                'username' => $config['username'],
                'password' => $config['password'],
                'authSource' => $config['options']['database'],
            ]);
        });
    }
}