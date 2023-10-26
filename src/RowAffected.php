<?php

namespace Gorse;

class RowAffected
{
    public int $rowAffected;

    /**
     * @param object $json
     */
    public static function fromJSON($json): RowAffected
    {
        $rowAffected              = new RowAffected();
        $rowAffected->rowAffected = $json->RowAffected;

        return $rowAffected;
    }
}
