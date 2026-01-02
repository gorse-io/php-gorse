<?php

namespace Gorse\Model;

use JsonSerializable;

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
