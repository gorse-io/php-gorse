<?php declare(strict_types=1);

namespace Gorse;

readonly class Feedback implements \JsonSerializable
{
    public function __construct(
        public string $feedbackType,
        public string $userId,
        public string $itemId,
        public string $timestamp,
        public ?string $comment = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        $result = [
            'FeedbackType' => $this->feedbackType,
            'UserId' => $this->userId,
            'ItemId' => $this->itemId,
            'Timestamp' => $this->timestamp,
        ];

        if ($this->comment) {
            $result['Comment'] = $this->comment;
        }

        return $result;
    }
}