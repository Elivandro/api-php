<?php

declare(strict_types=1);

namespace RetroAchievements\Providers;

use Illuminate\Support\ServiceProvider;
use RetroAchievements\Services\RetroAchievementsApiClient;

class RetroAchievementsProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/retroachievements.php',
            'retroachievements'
        );
    }

    public function boot(): void
    {
        $this->app->bind(RetroAchievementsApiClient::class, function () {
            $service = new RetroAchievementsApiClient;
            $service->setApiKey(config('retroachievements.api_key'));

            return $service;
        });
    }
}
