<?php

use GuzzleHttp\Exception\GuzzleException;

class User implements JsonSerializable
{
    public string $userId;
    public array $labels;

    public function jsonSerialize(): array
    {
        return [
            'UserId' => $this->userId,
            'Labels' => $this->labels,
        ];
    }

    public static function fromJSON($json): User
    {
        $user = new User();
        $user->userId = $json->UserId;
        $user->labels = $json->Labels;
        return $user;
    }
}

class RowAffected
{
    public int $rowAffected;

    public static function fromJSON($json): RowAffected
    {
        $rowAffected = new RowAffected();
        $rowAffected->rowAffected = $json->RowAffected;
        return $rowAffected;
    }
}

final class Gorse
{
    private string $endpoint;
    private string $apiKey;

    function __construct(string $endpoint, string $apiKey)
    {
        $this->endpoint = $endpoint;
        $this->apiKey = $apiKey;
    }

    /**
     * @throws GuzzleException
     */
    function insertUser(User $user): RowAffected
    {
        return $this->request('POST', '/api/user/', $user, RowAffected::class);
    }

    /**
     * @throws GuzzleException
     */
    function getUser(string $user_id): User
    {
        return $this->request('GET', '/api/user/' . $user_id, null, User::class);
    }

    /**
     * @throws GuzzleException
     */
    function deleteUser(string $user_id): RowAffected
    {
        return $this->request('DELETE', '/api/user/' . $user_id, null, RowAffected::class);
    }

    /**
     * @throws GuzzleException
     */
    private function request(string $method, string $uri, $body, $return_type)
    {
        $client = new GuzzleHttp\Client(['base_uri' => $this->endpoint]);
        $options = [GuzzleHttp\RequestOptions::HEADERS => ['X-API-Key' => $this->apiKey]];
        if ($body != null) {
            $options[GuzzleHttp\RequestOptions::JSON] = $body;
        }
        $response = $client->request($method, $uri, $options);
        return $return_type::fromJSON(json_decode($response->getBody()));
    }
}