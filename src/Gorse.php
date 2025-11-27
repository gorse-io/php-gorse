<?php

use GuzzleHttp\Exception\GuzzleException;

class User implements JsonSerializable
{
    public string $userId;
    public array $labels;
    public string $comment;

    public function __construct(string $userId, array $labels, string $comment = "")
    {
        $this->userId = $userId;
        $this->labels = $labels;
        $this->comment = $comment;
    }

    public function jsonSerialize(): array
    {
        return [
            'UserId' => $this->userId,
            'Labels' => $this->labels,
            'Comment' => $this->comment,
        ];
    }

    public static function fromJSON($json): User
    {
        return new User($json->UserId, (array) $json->Labels, $json->Comment);
    }
}

class Feedback implements JsonSerializable
{
    public string $feedback_type;
    public string $user_id;
    public string $item_id;
    public float $value;
    public string $timestamp;

    public function __construct(string $feedback_type, string $user_id, string $item_id, float $value, string $timestamp)
    {
        $this->feedback_type = $feedback_type;
        $this->user_id = $user_id;
        $this->item_id = $item_id;
        $this->value = $value;
        $this->timestamp = $timestamp;
    }

    public function jsonSerialize(): array
    {
        return [
            'FeedbackType' => $this->feedback_type,
            'UserId' => $this->user_id,
            'ItemId' => $this->item_id,
            'Value' => $this->value,
            'Timestamp' => $this->timestamp,
        ];
    }
}

class Item implements JsonSerializable
{
    public string $itemId;
    public bool $isHidden;
    public array $labels;
    public array $categories;
    public string $timestamp;
    public string $comment;

    public function __construct(string $itemId, bool $isHidden, array $labels, array $categories, string $timestamp, string $comment)
    {
        $this->itemId = $itemId;
        $this->isHidden = $isHidden;
        $this->labels = $labels;
        $this->categories = $categories;
        $this->timestamp = $timestamp;
        $this->comment = $comment;
    }

    public function jsonSerialize(): array
    {
        return [
            'ItemId' => $this->itemId,
            'IsHidden' => $this->isHidden,
            'Labels' => $this->labels,
            'Categories' => $this->categories,
            'Timestamp' => $this->timestamp,
            'Comment' => $this->comment,
        ];
    }

    public static function fromJSON($json): Item
    {
        return new Item($json->ItemId, $json->IsHidden, (array) $json->Labels, (array) $json->Categories, $json->Timestamp, $json->Comment);
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
    function insertItem(Item $item): RowAffected
    {
        return RowAffected::fromJSON($this->request('POST', '/api/item/', $item));
    }

    /**
     * @throws GuzzleException
     */
    function getItem(string $item_id): Item
    {
        return Item::fromJSON($this->request('GET', '/api/item/' . $item_id, null));
    }

    /**
     * @throws GuzzleException
     */
    function deleteItem(string $item_id): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', '/api/item/' . $item_id, null));
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
    function deleteFeedback(string $feedback_type, string $user_id, string $item_id): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', '/api/feedback/' . $feedback_type . '/' . $user_id . '/' . $item_id, null));
    }

    /**
     * @throws GuzzleException
     */
    function getRecommend(string $user_id, int $n = 10): array
    {
        return $this->request('GET', '/api/recommend/' . $user_id . '?n=' . $n, null);
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