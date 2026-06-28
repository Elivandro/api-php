<?php

declare(strict_types=1);

namespace RetroAchievements\Tests\Unit;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RetroAchievements\Exceptions\ApiException;
use RetroAchievements\Exceptions\InvalidApiKeyException;
use RetroAchievements\Exceptions\InvalidDateException;
use RetroAchievements\Services\RetroAchievementsApiClient;

class RetroAchievementsTest extends TestCase
{
    protected RetroAchievementsApiClient $raService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->raService = new RetroAchievementsApiClient;
    }

    private function makeService(array $responses): RetroAchievementsApiClient
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $service = $this->raService;
        $service->setHttpHandler($stack);

        return $service;
    }

    private function fixture(string $name): string
    {
        return file_get_contents(__DIR__.'/../Fixtures/'.$name.'.json');
    }

    public function test_get_user_profile_returns_object(): void
    {
        $service = $this->makeService([
            new Response(200, ['Content-Type' => 'application/json'], $this->fixture('user_profile')),
        ]);

        $result = $service->getUserProfile('MaxMilyin');

        $this->assertIsObject($result);
        $this->assertSame('MaxMilyin', $result->User);
        $this->assertTrue(isset($result->TotalPoints));
    }

    public function test_get_user_profile_sends_correct_username(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(200, [], $this->fixture('user_profile')),
        ]);
        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $this->raService->setHttpHandler($stack);
        $this->raService->getUserProfile('MaxMilyin');

        parse_str($container[0]['request']->getUri()->getQuery(), $query);

        $this->assertSame('MaxMilyin', $query['u']);
    }

    public function test_achievements_earned_between_converts_dates_to_timestamps(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([new Response(200, [], '{}')]);
        $stack = HandlerStack::create($mock);
        $stack->push($history);

        $this->raService->setHttpHandler($stack);
        $this->raService->getAchievementsEarnedBetween('User', '2024-01-01', '2024-01-31');

        parse_str($container[0]['request']->getUri()->getQuery(), $query);

        $this->assertSame((string) strtotime('2024-01-01'), $query['f']);
        $this->assertSame((string) strtotime('2024-01-31'), $query['t']);
    }

    public function test_throws_invalid_date_exception_on_invalid_date(): void
    {
        $this->expectException(InvalidDateException::class);

        $this->raService->getAchievementsEarnedBetween('User', 'invalid-date', 'another-invalid');
    }

    public function test_throws_invalid_date_exception_when_from_is_greater_than_to(): void
    {
        $this->expectException(InvalidDateException::class);

        $this->raService->getAchievementsEarnedBetween('User', '2024-12-31', '2024-01-01');
    }

    public function test_returns_empty_object_on_empty_response(): void
    {
        $service = $this->makeService([
            new Response(200, [], '{}'),
        ]);

        $result = $service->getUserProfile('nobody');

        $this->assertIsObject($result);
    }

    public function test_throws_invalid_api_key_exception_on_403(): void
    {
        $this->expectException(InvalidApiKeyException::class);

        $service = $this->makeService([
            new Response(403, [], 'Forbidden'),
        ]);

        $service->getUserProfile('User');
    }

    public function test_throws_api_exception_on_server_error(): void
    {
        $this->expectException(ApiException::class);

        $service = $this->makeService([
            new Response(500, [], 'Internal Server Error'),
        ]);

        $service->getUserProfile('User');
    }

    public function test_throws_api_exception_on_client_error(): void
    {
        $this->expectException(ApiException::class);

        $service = $this->makeService([
            new Response(404, [], 'Not Found'),
        ]);

        $service->getUserProfile('User');
    }
}
