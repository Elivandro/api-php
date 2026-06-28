<?php

declare(strict_types=1);

namespace RetroAchievements\Services;

use RetroAchievements\Exceptions\InvalidDateException;
use RetroAchievements\Traits\RetroAchievementsTrait;

class RetroAchievementsApiClient
{
    use RetroAchievementsTrait;

    public string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://retroachievements.org/API/';
    }

    public function getUserProfile(string $username): object
    {
        return $this->get('API_GetUserProfile.php', [
            'u' => $username,
        ]);
    }

    public function getUserRecentAchievements(string $username, ?int $minutes = 60): object
    {
        return $this->get('API_GetUserRecentAchievements.php', [
            'u' => $username,
            'm' => $minutes,
        ]);
    }

    public function getAchievementsEarnedBetween(string $username, string $from, string $to): object
    {
        $fromTs = strtotime($from);
        $toTs = strtotime($to);

        if ($fromTs === false || $toTs === false) {
            throw new InvalidDateException(
                "Invalid date: from={$from}, to={$to}"
            );
        }

        if ($fromTs > $toTs) {
            throw new InvalidDateException(
                "Start date ({$from}) cannot be greater than end date ({$to})"
            );
        }

        return $this->get('API_GetAchievementsEarnedBetween.php', [
            'u' => $username,
            'f' => $fromTs,
            't' => $toTs,
        ]);
    }

    public function getAchievementsEarnedOnDay(string $username, string $date): object
    {
        return $this->get('API_GetAchievementsEarnedOnDay.php', [
            'u' => $username,
            'd' => $date,
        ]);
    }

    public function getGameInfoAndUserProgress(string $username, int $gameId): object
    {
        return $this->get('API_GetGameInfoAndUserProgress.php', [
            'u' => $username,
            'g' => $gameId,
        ]);
    }

    public function getUserCompletionProgress(string $username): object
    {
        return $this->get('API_GetUserCompletionProgress.php', [
            'u' => $username,
        ]);
    }

    public function getUserAwards(string $username): object
    {
        return $this->get('API_GetUserAwards.php', [
            'u' => $username,
        ]);
    }

    public function getUserClaims(string $username): object
    {
        return $this->get('API_GetUserClaims.php', [
            'u' => $username,
        ]);
    }

    public function getUserGameRankAndScore(string $username, int $gameId): object
    {
        return $this->get('API_GetUserGameRankAndScore.php', [
            'u' => $username,
            'g' => $gameId,
        ]);
    }

    public function getUserPoints(string $username): object
    {
        return $this->get('API_GetUserPoints.php', [
            'u' => $username,
        ]);
    }

    /**
     * @param  array<int, int>  $gameIds
     */
    public function getUserProgress(string $username, array $gameIds): object
    {
        return $this->get('API_GetUserProgress.php', [
            'u' => $username,
            'i' => implode(',', $gameIds),
        ]);
    }

    public function getUserRecentlyPlayedGames(string $username, ?int $limit = 5): object
    {
        return $this->get('API_GetUserRecentlyPlayedGames.php', [
            'u' => $username,
            'c' => $limit,
        ]);
    }

    public function getUserSummary(string $username, ?int $totalGames = 0, ?int $totalAchievements = 3): object
    {
        return $this->get('API_GetUserSummary.php', [
            'u' => $username,
            'g' => $totalGames,
            'a' => $totalAchievements,
        ]);
    }

    public function getTopTenUsers(): object
    {
        return $this->get('API_GetTopTenUsers.php');
    }

    public function getGameInfo(int $gameId): object
    {
        return $this->get('API_GetGame.php', [
            'i' => $gameId,
        ]);
    }

    public function getGameInfoExtended(int $gameId): object
    {
        return $this->get('API_GetGameExtended.php', [
            'i' => $gameId,
        ]);
    }

    public function getConsoleIDs(): object
    {
        return $this->get('API_GetConsoleIDs.php');
    }

    public function getGameList(int $consoleId): object
    {
        return $this->get('API_GetGameList.php', [
            'i' => $consoleId,
        ]);
    }

    public function getFeedFor(string $username, int $count = 1, int $offset = 0): object
    {
        return $this->get('API_GetFeed.php', [
            'u' => $username,
            'c' => $count,
            'o' => $offset,
        ]);
    }

    public function getUserRankAndScore(string $username): object
    {
        return $this->get('API_GetUserRankAndScore.php', [
            'u' => $username,
        ]);
    }

    public function getUserCompletedGames(string $username): object
    {
        return $this->get('API_GetUserCompletedGames.php', [
            'u' => $username,
        ]);
    }

    public function getUserWantToPlayList(string $username, ?int $limit = 10): object
    {
        return $this->get('API_GetUserWantToPlayList.php', [
            'u' => $username,
            'c' => $limit,
        ]);
    }

    public function getAchievementOfTheWeek(): object
    {
        return $this->get('API_GetAchievementOfTheWeek.php');
    }
}
