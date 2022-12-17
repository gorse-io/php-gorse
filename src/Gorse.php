<?php

use GuzzleHttp\Exception\GuzzleException;

class User implements JsonSerializable
{
    public string $userId;
    public array $labels;

    public function __construct(string $userId, array $labels)
    {
        $this->userId = $userId;
        $this->labels = $labels;
    }

    public function jsonSerialize(): array
    {
        return [
            'UserId' => $this->userId,
            'Labels' => $this->labels,
        ];
    }

    public static function fromJSON($json): User
    {
        return new User($json->UserId, $json->Labels);
    }
}

class Feedback implements JsonSerializable
{
    public string $feedback_type;
    public string $user_id;
    public string $item_id;
    public string $timestamp;

    public function __construct(string $feedback_type, string $user_id, string $item_id, string $timestamp)
    {
        $this->feedback_type = $feedback_type;
        $this->user_id = $user_id;
        $this->item_id = $item_id;
        $this->timestamp = $timestamp;
    }

    public function jsonSerialize(): array
    {
        return [
            'FeedbackType' => $this->feedback_type,
            'UserId' => $this->user_id,
            'ItemId' => $this->item_id,
            'Timestamp' => $this->timestamp,
        ];
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
        return RowAffected::fromJSON($this->request('POST', '/api/user/', $user));
    }

    /**
     * @throws GuzzleException
     */
    function getUser(string $user_id): User
    {
        return User::fromJSON($this->request('GET', '/api/user/' . $user_id, null));
    }

    /**
     * @throws GuzzleException
     */
    function deleteUser(string $user_id): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', '/api/user/' . $user_id, null));
    }

    /**
     * @throws GuzzleException
     */
    function insertFeedback(array $feedback): RowAffected
    {
        return RowAffected::fromJSON($this->request('POST', '/api/feedback/', $feedback));
    }

    /**
     * @throws GuzzleException
     */
    function getRecommend(string $user_id): array
    {
        return $this->request('GET', '/api/recommend/' . $user_id, null);
    }

    /**
     * @throws GuzzleException
     */
    private function request(string $method, string $uri, $body)
    {
        $client = new GuzzleHttp\Client(['base_uri' => $this->endpoint]);
        $options = [GuzzleHttp\RequestOptions::HEADERS => ['X-API-Key' => $this->apiKey]];
        if ($body != null) {
            $options[GuzzleHttp\RequestOptions::JSON] = $body;
        }
        $response = $client->request($method, $uri, $options);
        return json_decode($response->getBody());
    }
}