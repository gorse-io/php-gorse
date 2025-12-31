<?php

namespace Gorse\Model;

use JsonSerializable;

class Item implements JsonSerializable
{
    public string $itemId;
    public bool $isHidden;
    public array $labels;
    public array $categories;
    public string $timestamp;
    public string $comment;

    public function __construct(string $itemId, bool $isHidden, array $categories, string $timestamp, array $labels, string $comment)
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
        return new Item($json->ItemId, $json->IsHidden, (array) $json->Categories, $json->Timestamp, (array) $json->Labels, $json->Comment);
    }
}
