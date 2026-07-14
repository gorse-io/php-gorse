<?php

namespace Gorse;

use Gorse\Model\Feedback;
use Gorse\Model\Item;
use Gorse\Model\RowAffected;
use Gorse\Model\Score;
use Gorse\Model\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use stdClass;

final class Gorse
{
    private string $endpoint;
    private string $apiKey;
    private Client $client;

    function __construct(string $endpoint, string $apiKey)
    {
        $this->endpoint = $endpoint;
        $this->apiKey = $apiKey;
        $this->client = new Client(['base_uri' => $this->endpoint]);
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
        return User::fromJSON($this->request('GET', '/api/user/' . urlencode($user_id), null));
    }

    /**
     * @throws GuzzleException
     */
    function deleteUser(string $user_id): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', '/api/user/' . urlencode($user_id), null));
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
        return Item::fromJSON($this->request('GET', '/api/item/' . urlencode($item_id), null));
    }

    /**
     * @return Item[]
     * @throws GuzzleException
     */
    function searchItems(string $query, int $n = 10): array
    {
        $items = [];
        $response = $this->request('GET', '/api/items', null, ['q' => $query, 'n' => $n]);
        foreach ($response->Items as $item) {
            $items[] = Item::fromJSON($item);
        }
        return $items;
    }

    /**
     * @throws GuzzleException
     */
    function deleteItem(string $item_id): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', '/api/item/' . urlencode($item_id), null));
    }

    /**
     * @throws GuzzleException
     */
    function updateItem(string $item_id, Item $item): RowAffected
    {
        return RowAffected::fromJSON($this->request('PATCH', '/api/item/' . urlencode($item_id), $item));
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
    function listFeedback(string $feedback_type, string $user_id, string $item_id): array
    {
        return $this->request('GET', '/api/feedback/' . urlencode($feedback_type) . '/' . urlencode($user_id) . '/' . urlencode($item_id), null);
    }

    /**
     * @throws GuzzleException
     */
    function getFeedback(string $user_id, string $item_id): array
    {
        return $this->request('GET', '/api/feedback/' . urlencode($user_id) . '/' . urlencode($item_id), null);
    }
    
    /**
     * @throws GuzzleException
     */
    function getFeedbackByType(string $feedback_type): array
    {
        return $this->request('GET', '/api/feedback/' . urlencode($feedback_type), null);
    }

    /**
     * @throws GuzzleException
     */
    function deleteFeedback(string $feedback_type, string $user_id, string $item_id): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', '/api/feedback/' . urlencode($feedback_type) . '/' . urlencode($user_id) . '/' . urlencode($item_id), null));
    }

    /**
     * Get recommendation with scores for a user.
     * Uses X-API-Version: 2 header to return scores.
     * @throws GuzzleException
     */
    function getRecommend(string $user_id, ?string $write_back_type = null, ?string $write_back_delay = null, int $n = 10, int $offset = 0): array
    {
        $params = ['n' => $n, 'offset' => $offset];
        if ($write_back_type) $params['write-back-type'] = $write_back_type;
        if ($write_back_delay) $params['write-back-delay'] = $write_back_delay;
        
        $scores = [];
        $response = $this->requestWithHeaders('GET', '/api/recommend/' . urlencode($user_id), null, $params, ['X-API-Version' => '2']);
        foreach ($response as $score) {
            $scores[] = Score::fromJSON($score);
        }
        return $scores;
    }
    
    /**
     * @throws GuzzleException
     */
    function getSessionRecommend(array $feedback, int $n = 10): array
    {
        $scores = [];
        $response = $this->request('POST', '/api/session/recommend?n=' . $n, $feedback);
        foreach ($response as $score) {
            $scores[] = Score::fromJSON($score);
        }
        return $scores;
    }

    /**
     * @throws GuzzleException
     */
    function getNeighbors(string $item_id, int $n = 10, int $offset = 0): array
    {
         return $this->getItemNeighbors('neighbors', $item_id, $n, $offset);
    }
    
    /**
     * @throws GuzzleException
     */
    function getItemNeighbors(string $name, string $item_id, int $n = 10, int $offset = 0): array
    {
        $scores = [];
        $response = $this->request('GET', "/api/item-to-item/" . urlencode($name) . "/" . urlencode($item_id), null, ['n' => $n, 'offset' => $offset]);
        foreach ($response as $score) {
            $scores[] = Score::fromJSON($score);
        }
        return $scores;
    }

    /**
     * @throws GuzzleException
     */
    function getUserNeighbors(string $name, string $user_id, int $n = 10, int $offset = 0): array
    {
        $scores = [];
        $response = $this->request('GET', "/api/user-to-user/" . urlencode($name) . "/" . urlencode($user_id), null, ['n' => $n, 'offset' => $offset]);
        foreach ($response as $score) {
            $scores[] = Score::fromJSON($score);
        }
        return $scores;
    }

    /**
     * @throws GuzzleException
     */
    function getNonPersonalized(string $name, ?string $user_id = null,  int $n = 10, int $offset = 0): array
    {
        $params = ['n' => $n, 'offset' => $offset];
        if ($user_id) $params['user-id'] = $user_id;

        $scores = [];
        $response = $this->request('GET', "/api/non-personalized/" . urlencode($name), null, $params);
        foreach ($response as $score) {
            $scores[] = Score::fromJSON($score);
        }
        return $scores;
    }

    /**
     * @throws GuzzleException
     */
    function getLatest(?string $user_id = null, int $n = 10, int $offset = 0): array
    {
        $params = ['n' => $n, 'offset' => $offset];
        if ($user_id) $params['user-id'] = $user_id;

        $scores = [];
        $response = $this->request('GET', '/api/latest', null, $params);
        foreach ($response as $score) {
            $scores[] = Score::fromJSON($score);
        }
        return $scores;
    }

    /**
     * @throws GuzzleException
     */
    private function request(string $method, string $uri, $body, array $query = [])
    {
        return $this->requestWithHeaders($method, $uri, $body, $query, []);
    }

    /**
     * @throws GuzzleException
     */
    private function requestWithHeaders(string $method, string $uri, $body, array $query = [], array $headers = [])
    {
        $options = [RequestOptions::HEADERS => array_merge(['X-API-Key' => $this->apiKey], $headers)];
        if ($body != null) {
            $options[RequestOptions::JSON] = $body;
        }
        if (!empty($query)) {
            $options[RequestOptions::QUERY] = $query;
        }
        $response = $this->client->request($method, $uri, $options);
        return json_decode($response->getBody());
    }
}
