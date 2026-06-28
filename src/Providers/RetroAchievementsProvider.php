<?php

declare(strict_types=1);

namespace RetroAchievements\Providers;

use Illuminate\Support\ServiceProvider;
use RetroAchievements\Services\RetroAchievementsApiClient;

class RetroAchievementsProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/retroachievements.php' => config_path('retroachievements.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/retroachievements.php',
            'retroachievements'
        );

        $this->app->bind(RetroAchievementsApiClient::class, function ($app) {
            $service = new RetroAchievementsApiClient;
            $service->setApiKey($app['config']['retroachievements.api_key'] ?? '');

            return $service;
        });
    }
}
