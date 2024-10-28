<?php declare(strict_types=1);

namespace Gorse;

use GuzzleHttp\Exception\GuzzleException;

final readonly class Gorse
{
    function __construct(
        private string $endpoint,
        private string $apiKey
    ) {
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
    function getUser(string $userId): User
    {
        return User::fromJSON($this->request('GET', '/api/user/' . $userId));
    }

    /**
     * @throws GuzzleException
     */
    function deleteUser(string $userId): RowAffected
    {
        return RowAffected::fromJSON($this->request('DELETE', '/api/user/' . $userId));
    }

    /**
     * @param Feedback|Feedback[] $feedback
     * @throws GuzzleException
     */
    function insertFeedback(mixed $feedback): RowAffected
    {
        if ($feedback instanceof Feedback) {
            $feedback = [$feedback];
        }

        return RowAffected::fromJSON($this->request('POST', '/api/feedback/', $feedback));
    }

    /**
     * @throws GuzzleException
     */
    function getRecommend(string $userId): array
    {
        return $this->request('GET', '/api/recommend/' . $userId);
    }

    /**
     * @throws GuzzleException
     */
    private function request(string $method, string $uri, \JsonSerializable|array|null $body = null)
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