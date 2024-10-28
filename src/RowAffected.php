<?php declare(strict_types=1);

namespace Gorse;

readonly class RowAffected
{
    public function __construct(
        public int $rowAffected
    ) {
    }

    public static function fromJSON(object $json): RowAffected
    {
        return new RowAffected($json->RowAffected);
    }
}