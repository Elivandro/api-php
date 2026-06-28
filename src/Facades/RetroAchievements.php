<?php

declare(strict_types=1);

namespace RetroAchievements\Facades;

use Illuminate\Support\Facades\Facade;
use Override;
use RetroAchievements\Services\RetroAchievementsApiClient;

/**
 * Facade for consuming the RetroAchievements API.
 *
 * Important note:
 * Each request should be made with the user's own API key.
 * Use the `setApiKey($apiKey)` method before making calls when needed.
 *
 * Authentication
 *
 * @method static void setApiKey(string $apiKey)
 *
 * User
 * @method static object getUserProfile(string $username)
 * @method static object getUserRecentAchievements(string $username, ?int $minutes = 60)
 * @method static object getAchievementsEarnedBetween(string $username, string $from, string $to)
 * @method static object getAchievementsEarnedOnDay(string $username, string $date)
 * @method static object getGameInfoAndUserProgress(string $username, int $gameId)
 * @method static object getUserCompletionProgress(string $username)
 * @method static object getUserAwards(string $username)
 * @method static object getUserClaims(string $username)
 * @method static object getUserGameRankAndScore(string $username, int $gameId)
 * @method static object getUserPoints(string $username)
 * @method static object getUserProgress(string $username, array<int, int> $gameIds)
 * @method static object getUserRecentlyPlayedGames(string $username, ?int $limit = 5)
 * @method static object getUserSummary(string $username, ?int $totalGames = 0, ?int $totalAchievements = 3)
 * @method static object getUserCompletedGames(string $username)
 * @method static object getUserWantToPlayList(string $username, ?int $limit = 10)
 * @method static object getUserRankAndScore(string $username)
 *
 * Games & Consoles
 * @method static object getGameInfo(int $gameId)
 * @method static object getGameInfoExtended(int $gameId)
 * @method static object getConsoleIDs()
 * @method static object getGameList(int $consoleId)
 *
 * Feed
 * @method static object getFeedFor(string $username, int $count, int $offset = 0)
 *
 * Ranking
 * @method static object getTopTenUsers()
 *
 * Events
 * @method static object getAchievementOfTheWeek()
 *
 * @see RetroAchievementsApiClient
 */
class RetroAchievements extends Facade
{
    #[Override]
    protected static function getFacadeAccessor(): string
    {
        return RetroAchievementsApiClient::class;
    }
}
