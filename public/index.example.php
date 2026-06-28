<?php

require_once __DIR__ . '/../vendor/autoload.php';

// ---------------------------------------------------------------------------
// Standalone usage
// ---------------------------------------------------------------------------

use RetroAchievements\Services\RetroAchievementsApiClient;

$api = new RetroAchievementsApiClient();
$api->setApiKey('your-api-key-here');

$profile                   = $api->getUserProfile('MaxMilyin');
$achievementsEarnedBetween = $api->getAchievementsEarnedBetween('MaxMilyin', '2024-01-01', '2024-01-30');
$userSummary               = $api->getUserSummary('MaxMilyin', 1, 3);
$consoleIds                = $api->getConsoleIDs();

var_dump($profile);
var_dump($achievementsEarnedBetween);

// ---------------------------------------------------------------------------
// Laravel bootstrap (only needed outside a Laravel project)
// ---------------------------------------------------------------------------

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Facade;
use RetroAchievements\Facades\RetroAchievements;
use RetroAchievements\Providers\RetroAchievementsProvider;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = new Application(__DIR__ . '/../');

$app->singleton('config', function () {
    return new Repository([
        'retroachievements' => require __DIR__ . '/../src/config/retroachievements.php',
    ]);
});

Facade::setFacadeApplication($app);

$provider = new RetroAchievementsProvider($app);
$provider->register();
$provider->boot();

// ---------------------------------------------------------------------------
// Laravel usage
// In a real Laravel project, everything above is handled automatically.
// Just use the Facade directly:
// ---------------------------------------------------------------------------

RetroAchievements::setApiKey('your-api-key-here'); // optional if set in .env

$profile      = RetroAchievements::getUserProfile('MaxMilyin');
$achievements = RetroAchievements::getUserRecentAchievements('MaxMilyin', 525600);

var_dump($profile);
var_dump($achievements);