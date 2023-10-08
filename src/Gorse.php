<?php

namespace Gorse;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

final class Gorse
{
    private string $endpoint;
    private string $apiKey;

    public function __construct(string $endpoint, string $apiKey)
    {
        $this->endpoint = $endpoint;
        $this->apiKey   = $apiKey;
    }

    /**
     * @throws GuzzleException
     */
    public function insertUser(User $user): RowAffected
    {
        return RowAffected::fromJSON($this->request('POST', '/api/user/', $user));
    }

    /**
     * @throws GuzzleException
     */
    public function getUser(string $user_id): User
    {
        return User::fromJSON($this->request('GET', '/api/user/' . $user_id, null));
    }

    /**
     * @throws GuzzleException
     */
    public function deleteUser(string $user_id): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', '/api/user/' . $user_id, null));
    }

    /**
     * @throws GuzzleException
     */
    public function insertFeedback(array $feedback): RowAffected
    {
        return RowAffected::fromJSON($this->request('POST', '/api/feedback/', $feedback));
    }

    /**
     * @throws GuzzleException
     */
    public function getRecommend(string $user_id): array
    {
        return $this->request('GET', '/api/recommend/' . $user_id, null);
    }

    /**
     * @throws GuzzleException
     */
    private function request(string $method, string $uri, $body)
    {
        $client  = new Client(['base_uri' => $this->endpoint]);
        $options = [RequestOptions::HEADERS => ['X-API-Key' => $this->apiKey]];
        if ($body != null) {
            $options[RequestOptions::JSON] = $body;
        }
        $response = $client->request($method, $uri, $options);

        return json_decode($response->getBody());
    }
}
