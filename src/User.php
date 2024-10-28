<?php declare(strict_types=1);

namespace Gorse;

readonly class User implements \JsonSerializable
{

    public function __construct(
        public string $userId,
        public array $labels
    ) {
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
