<?php

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
}

class RowAffected
{
    public int $rowAffected;

    function __construct(int $rowAffected)
    {
        $this->rowAffected = $rowAffected;
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

    function insertUser(User $user): RowAffected
    {
        $client = new GuzzleHttp\Client(['base_uri' => $this->endpoint]);
        $response = $client->request('POST', '/api/user', [
            GuzzleHttp\RequestOptions::JSON => $user,
            GuzzleHttp\RequestOptions::HEADERS => ['X-API-Key' => $this->apiKey]
        ]);
        return new RowAffected(json_decode($response->getBody())->RowAffected);
    }
}