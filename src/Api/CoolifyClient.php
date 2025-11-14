<?php

namespace App\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * A simple client for interacting with the Coolify API using Guzzle.
 */
class CoolifyClient
{
    private readonly Client $httpClient;

    public function __construct(string $apiUrl, string $apiToken)
    {
        if (empty($apiUrl) || empty($apiToken)) {
            throw new Exception('Coolify API URL or Token is missing.');
        }

        $this->httpClient = new Client([
            'base_uri' => rtrim($apiUrl, '/') . '/api/v1/',
            'headers' => [
                'Authorization' => 'Bearer ' . $apiToken,
                'Accept' => 'application/json',
            ],
            'timeout' => 10.0,
        ]);
    }

    /**
     * Fetches all resources from the Coolify API.
     */
    public function getResources(): array
    {
        try {
            $response = $this->httpClient->get('resources');
            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody, true) ?? [];
        } catch (GuzzleException $e) {
            throw new Exception(
                'Failed to fetch resources from Coolify: ' . $e->getMessage(),
                0,
                $e,
            );
        }
    }
}
