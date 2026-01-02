<?php

namespace Gorse\Model;

class RowAffected
{
    public int $rowAffected;

    public static function fromJSON($json): RowAffected
    {
        $rowAffected = new RowAffected();
        $rowAffected->rowAffected = $json->RowAffected;
        return $rowAffected;
    }
}
