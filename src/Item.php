<?php

namespace Gorse;

/**
 * Documentation:
 * @see https://gorse.io/zh/docs/master/concepts/data-objects.html#物品
 */
class Item implements \JsonSerializable
{
    /**
     * @param array<string> $categories
     * @param string        $timestamp  ISO 8601 format (e.g., 2020-02-02T20:20:02.02Z)
     * @param array<string> $labels
     */
    public function __construct(
        public string $item_id,
        public bool $is_hidden,
        public array $categories,
        public string $timestamp,
        public array $labels,
        public string $comment,
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'ItemId'     => $this->item_id,
            'IsHidden'   => $this->is_hidden,
            'Categories' => $this->categories,
            'Timestamp'  => $this->timestamp,
            'Labels'     => $this->labels,
            'Comment'    => $this->comment,
        ];
    }

    /**
     * @param object $json
     */
    public static function fromJSON(object $json): Item
    {
        return new Item(
            $json->ItemId,
            $json->IsHidden,
            $json->Categories,
            $json->Timestamp,
            $json->Labels,
            $json->Comment,
        );
    }
}
