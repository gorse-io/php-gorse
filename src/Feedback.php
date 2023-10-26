<?php

namespace Gorse;

class Feedback implements \JsonSerializable
{
    public string $feedback_type;
    public string $user_id;
    public string $item_id;
    public string $timestamp;

    public function __construct(
        string $feedback_type,
        string $user_id,
        string $item_id,
        string $timestamp
    ) {
        $this->feedback_type = $feedback_type;
        $this->user_id       = $user_id;
        $this->item_id       = $item_id;
        $this->timestamp     = $timestamp;
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
