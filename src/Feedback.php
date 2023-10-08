<?php

namespace Gorse;

class Feedback implements \JsonSerializable
{
    public function __construct(
        public string $feedback_type,
        public string $user_id,
        public string $item_id,
        public string $timestamp
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'FeedbackType' => $this->feedback_type,
            'UserId'       => $this->user_id,
            'ItemId'       => $this->item_id,
            'Timestamp'    => $this->timestamp,
        ];
    }
}
