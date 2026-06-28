<p align="center" dir="auto"><a href="https://retroachievements.org" rel="nofollow"><img src="https://raw.githubusercontent.com/RetroAchievements/RAWeb/master/public/assets/images/ra-icon.webp" width="200" alt="RetroAchievements Logo" style="max-width: 100%;"></a></p>
<h1 align="center">RetroAchievements Web API Client</h1>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/retroachievements/api.svg?style=flat-square)](https://packagist.org/packages/retroachievements/api)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/retroachievements/api/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/retroachievements/api/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/retroachievements/api/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/retroachievements/api/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/retroachievements/api.svg?style=flat-square)](https://packagist.org/packages/retroachievements/api)

## Requirements

- PHP 8.2+
- Guzzle 7.0+

## Installation

Install the package via composer:

```shell
composer require retroachievements/api
```

## Configuration

Add your API key to your `.env` file:

```
RETROACHIEVEMENTS_API_KEY=your-api-key-here
```

You can get your API key at [retroachievements.org/settings](https://retroachievements.org/settings).

## Usage

### Standalone

in your php file add:
```php
<?php

require_once __DIR__.'/../vendor/autoload.php';

use RetroAchievements\Services\RetroAchievementsApiClient;

$api = new RetroAchievementsApiClient;
$api->setApiKey('your-api-key-here');

$profile      = $api->getUserProfile('MaxMilyin');
$achievements = $api->getUserRecentAchievements('MaxMilyin', 60);

echo $profile->User;
echo $profile->TotalPoints;

foreach ($achievements as $achievement) {
    echo $achievement->Title;
    echo $achievement->GameTitle;
}
```

### Laravel

Publish the config file:

```shell
php artisan vendor:publish --provider="RetroAchievements\Providers\RetroAchievementsProvider"
```

### Manual Registration (if auto-discovery fails)

If the provider is not discovered automatically, add it manually to `bootstrap/providers.php` (Laravel 11+):

```php
return [
    RetroAchievements\Providers\RetroAchievementsProvider::class,
];
```

Use the Facade:

```php
use RetroAchievements\Facades\RetroAchievements;

$profile      = RetroAchievements::getUserProfile('MaxMilyin');
$achievements = RetroAchievements::getUserRecentAchievements('MaxMilyin', 60);
```

Switching API keys at runtime:

```php
foreach ($users as $user) {
    RetroAchievements::setApiKey($user->ra_api_key);

    $profile = RetroAchievements::getUserProfile($user->ra_username);
}
```

## Error Handling

```php
use RetroAchievements\Exceptions\ApiException;
use RetroAchievements\Exceptions\InvalidApiKeyException;
use RetroAchievements\Exceptions\InvalidDateException;

try {
    $profile = RetroAchievements::getUserProfile('NickGoat1990');

} catch (InvalidApiKeyException $e) {
    // Invalid or missing API key
} catch (InvalidDateException $e) {
    // Invalid date format or range
} catch (ApiException $e) {
    // General API error
}
```

## Available Methods

### User
| Method | Description |
|--------|-------------|
| `getUserProfile(string $username)` | Get user profile |
| `getUserRecentAchievements(string $username, ?int $minutes)` | Get recent achievements |
| `getAchievementsEarnedBetween(string $username, string $from, string $to)` | Get achievements earned in a date range |
| `getAchievementsEarnedOnDay(string $username, string $date)` | Get achievements earned on a specific day |
| `getUserPoints(string $username)` | Get user points |
| `getUserRankAndScore(string $username)` | Get user rank and score |
| `getUserSummary(string $username)` | Get user summary |
| `getUserCompletionProgress(string $username)` | Get user completion progress |
| `getUserAwards(string $username)` | Get user awards |
| `getUserRecentlyPlayedGames(string $username, ?int $limit)` | Get recently played games |
| `getUserWantToPlayList(string $username, ?int $limit)` | Get want to play list |
| `getUserCompletedGames(string $username)` | Get completed games |
| `getUserProgress(string $username, array $gameIds)` | Get user progress for specific games |
| `getUserClaims(string $username)` | Get user claims |
| `getUserGameRankAndScore(string $username, int $gameId)` | Get user rank and score for a specific game |

### Games & Consoles
| Method | Description |
|--------|-------------|
| `getGameInfo(int $gameId)` | Get game information |
| `getGameInfoExtended(int $gameId)` | Get extended game information |
| `getGameInfoAndUserProgress(string $username, int $gameId)` | Get game info with user progress |
| `getGameList(int $consoleId)` | Get game list for a console |
| `getConsoleIDs()` | Get all console IDs |

### Feed & Ranking
| Method | Description |
|--------|-------------|
| `getTopTenUsers()` | Get top ten users |
| `getFeedFor(string $username, int $count, int $offset)` | Get feed for a user |
| `getAchievementOfTheWeek()` | Get achievement of the week |

## Testing

```shell
composer test
```

## Quality

```shell
composer check   # runs pint + phpstan + phpunit
composer fix     # auto-fix code style
composer stan    # phpstan only
```

## Contributing

See [Contribution Guidelines](CONTRIBUTING.md) and [Code of Conduct](CODE_OF_CONDUCT.md).

## Security Vulnerabilities

Please review [our security policy](../../security/policy).

## Credits

- [All Contributors](../../contributors)

## License

MIT License (MIT). See [License File](LICENSE.md).