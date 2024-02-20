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
    function insertFeedback(array $feedbacks): RowAffected
    {
        return RowAffected::fromJSON($this->request('POST', '/api/feedback', $feedbacks));
    }
    
    /**
     * @throws GuzzleException
     */
    function putFeedback(array $feedbacks): RowAffected
    {
        return RowAffected::fromJSON($this->request('PUT', '/api/feedback', $feedbacks));
    }
    
    /**
     * @throws GuzzleException
     */
    function getFeedback(string $cursor, int $n): array
    {
        return $this->request('GET', '/api/feedback?cursor=' . $cursor . '&n=' . $n, null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getFeedbacksWithType(string $feedbackType, string $cursor, int $n): array
    {
        return $this->request('GET', '/api/feedback/' . $feedbackType . '?cursor=' . $cursor . '&n=' . $n, null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getFeedbackWithUserItem(string $userId, string $itemId): array
    {
        return $this->request('GET', '/api/feedback/' . $userId . '/' . $itemId, null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getFeedbackWithTypeUserItem(string $feedbackType, string $userId, string $itemId): Feedback
    {
        return Feedback::fromJSON($this->request('GET', '/api/feedback/' . $feedbackType . '/' . $userId . '/' . $itemId, null));
    }
    
    /**
     * @throws GuzzleException
     */
    function delFeedback(string $feedbackType, string $userId, string $itemId): Feedback
    {
        return Feedback::fromJSON($this->request('DELETE', '/api/feedback/' . $feedbackType . '/' . $userId . '/' . $itemId, null));
    }
    
    /**
     * @throws GuzzleException
     */
    function delFeedbackWithUserItem(string $userId, string $itemId): array
    {
        return $this->request('DELETE', '/api/feedback/' . $userId . '/' . $itemId, null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemFeedbacks(string $itemId)
    {
        return $this->request('GET', "/api/item/$itemId/feedback", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemFeedbacksWithType(string $itemId, string $feedbackType)
    {
        return $this->request('GET', "/api/item/$itemId/feedback/$feedbackType", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getUserFeedbacks(string $userId)
    {
        return $this->request('GET', "/api/user/$userId/feedback", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getUserFeedbacksWithType(string $userId, string $feedbackType)
    {
        return $this->request('GET', "/api/user/$userId/feedback/$feedbackType", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemLatest(string $userId, int $n, int $offset)
    {
        return $this->request('GET', "/api/latest?user-id=$userId&n=$n&offset=$offset", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemLatestWithCategory(string $userId, string $category, int $n, int $offset)
    {
        return $this->request('GET', "/api/latest?user-id=$userId&category=$category&n=$n&offset=$offset", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemPopular(string $userId, int $n, int $offset)
    {
        return $this->request('GET', "/api/popular?user-id=$userId&n=$n&offset=$offset", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemPopularWithCategory(string $userId, string $category, int $n, int $offset)
    {
        return $this->request('GET', "/api/popular/$category?user-id=$userId&n=$n&offset=$offset", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemRecommend(string $userId, array $categories, string $writeBackType, string $writeBackDelay, int $n, int $offset)
    {
        $queryCategories = http_build_query(['category' => $categories], '', '&');
        return $this->request('GET', "/api/recommend/$userId?write-back-type=$writeBackType&write-back-delay=$writeBackDelay&n=$n&offset=$offset&$queryCategories", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemRecommendWithCategory(string $userId, string $category, string $writeBackType, string $writeBackDelay, int $n, int $offset)
    {
        return $this->request('GET', "/api/recommend/$userId/$category?write-back-type=$writeBackType&write-back-delay=$writeBackDelay&n=$n&offset=$offset", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getSessionItemRecommend(array $feedbacks, int $n, int $offset)
    {
        return $this->request('POST', "/api/session/recommend?n=$n&offset=$offset", $feedbacks);
    }
    
    /**
     * @throws GuzzleException
     */
    function getSessionItemRecommendWithCategory(array $feedbacks, string $category, int $n, int $offset)
    {
        return $this->request('POST', "/api/session/recommend/$category?n=$n&offset=$offset", $feedbacks);
    }
    
    /**
     * @throws GuzzleException
     */
    function getUserNeighbors(string $userId, int $n, int $offset)
    {
        return $this->request('GET', "/api/user/$userId/neighbors?n=$n&offset=$offset", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemNeighbors(string $itemId, int $n, int $offset)
    {
        return $this->request('GET', "/api/item/$itemId/neighbors?n=$n&offset=$offset", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemNeighborsWithCategory(string $itemId, string $category, int $n, int $offset)
    {
        return $this->request('GET', "/api/item/$itemId/neighbors/$category?n=$n&offset=$offset", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function insertUser(User $user): RowAffected
    {
        return RowAffected::fromJSON($this->request('POST', '/api/user', $user->jsonSerialize()));
    }
    
    /**
     * @throws GuzzleException
     */
    function insertUsers(array $users): RowAffected
    {
        $usersData = array_map(function($user) {
            return $user->jsonSerialize();
        }, $users);
        return RowAffected::fromJSON($this->request('POST', '/api/users', $usersData));
    }
    
    /**
     * @throws GuzzleException
     */
    function updateUser(string $userId, array $userPatch): RowAffected
    {
        return RowAffected::fromJSON($this->request('PATCH', "/api/user/$userId", $userPatch));
    }
    
    /**
     * @throws GuzzleException
     */
    function getUser(string $userId): User
    {
        return User::fromJSON($this->request('GET', "/api/user/$userId", null));
    }
    
    /**
     * @throws GuzzleException
     */
    function getUsers(string $cursor = '', int $n = 0)
    {
        $queryParams = http_build_query(['cursor' => $cursor, 'n' => $n]);
        return $this->request('GET', "/api/users?$queryParams", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function deleteUser(string $userId): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', "/api/user/$userId", null));
    }
    
    /**
     * @throws GuzzleException
     */
    function insertItem(array $item): RowAffected
    {
        return RowAffected::fromJSON($this->request('POST', '/api/item', $item));
    }
    
    /**
     * @throws GuzzleException
     */
    function insertItems(array $items): RowAffected
    {
        return RowAffected::fromJSON($this->request('POST', '/api/items', $items));
    }
    
    /**
     * @throws GuzzleException
     */
    function updateItem(string $itemId, array $itemPatch): RowAffected
    {
        return RowAffected::fromJSON($this->request('PATCH', "/api/item/$itemId", $itemPatch));
    }
    
    /**
     * @throws GuzzleException
     */
    function getItem(string $itemId)
    {
        return $this->request('GET', "/api/item/$itemId", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItems(string $cursor = '', int $n = 0)
    {
        $queryParams = http_build_query(['cursor' => $cursor, 'n' => $n]);
        return $this->request('GET', "/api/items?$queryParams", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function deleteItem(string $itemId): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', "/api/item/$itemId", null));
    }
    
    /**
     * @throws GuzzleException
     */
    function putItemCategory(string $itemId, string $category): RowAffected
    {
        return RowAffected::fromJSON($this->request('PUT', "/api/item/$itemId/category/$category", null));
    }
    
    /**
     * @throws GuzzleException
     */
    function delItemCategory(string $itemId, string $category): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', "/api/item/$itemId/category/$category", null));
    }
    
    
    /**
     * @throws GuzzleException
     */
    function healthLive()
    {
        return $this->request('GET', "/api/health/live", null);
    }
    
    /**
     * @throws GuzzleException
     */
    function healthReady()
    {
        return $this->request('GET', "/api/health/ready", null);
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
