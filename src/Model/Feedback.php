<?php

namespace Gorse\Model;

use JsonSerializable;

class Feedback implements JsonSerializable
{
    public string $feedback_type;
    public string $user_id;
    public string $item_id;
    public float $value;
    public string $timestamp;

    public function __construct(string $feedback_type, string $user_id, string $item_id, string $timestamp, float $value = 1.0)
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
