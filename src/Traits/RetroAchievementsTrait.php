<?php

declare(strict_types=1);

namespace RetroAchievements\Traits;

use ArrayObject;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\HandlerStack;
use RetroAchievements\Exceptions\ApiException;
use RetroAchievements\Exceptions\InvalidApiKeyException;

/**
 * @property string $apiKey
 * @property string $baseUrl
 */
trait RetroAchievementsTrait
{
    private ?Client $client = null;

    private string $apiKey = '';

    /**
     * Set a custom HTTP handler for testing purposes.
     */
    public function setHttpHandler(HandlerStack $stack): void
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'handler' => $stack,
        ]);
    }

    /**
     * Resolve the Guzzle HTTP client instance.
     */
    protected function getClient(): Client
    {
        return $this->client ??= new Client([
            'base_uri' => $this->baseUrl,
            'http_errors' => false,
        ]);
    }

    /**
     * Perform a GET request to the given endpoint.
     *
     * @param  array<string, mixed>  $params
     */
    public function get(string $endpoint, array $params = []): object
    {
        return $this->request('get', $endpoint, $params);
    }

    /**
     * Perform an HTTP request and return the decoded response.
     *
     * @param  array<string, mixed>  $params
     *
     * @throws InvalidApiKeyException
     * @throws ApiException
     */
    protected function request(string $method, string $endpoint, array $params): object
    {
        try {
            $response = $this->getClient()->request($method, $endpoint, [
                'query' => array_merge(['y' => $this->apiKey], $params),
            ]);

            $decoded = json_decode($response->getBody()->getContents(), false) ?? (object) [];

            if (is_array($decoded)) {
                return new ArrayObject($decoded);
            }

            return $decoded;

        } catch (ClientException $e) {
            $status = $e->getResponse()->getStatusCode();

            if ($status === 401 || $status === 403) {
                throw new InvalidApiKeyException(
                    'Invalid or missing API key.',
                    $status,
                    $e
                );
            }

            throw new ApiException(
                "Client error: HTTP {$status} on endpoint {$endpoint}",
                $status,
                $e
            );

        } catch (ServerException $e) {
            $status = $e->getResponse()->getStatusCode();

            throw new ApiException(
                "RetroAchievements server error: HTTP {$status} on endpoint {$endpoint}",
                $status,
                $e
            );

        } catch (ConnectException $e) {
            throw new ApiException(
                'Could not connect to RetroAchievements.',
                0,
                $e
            );
        }
    }

    /**
     * Set the API key and reset the HTTP client.
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
        $this->client = null;
    }
}
