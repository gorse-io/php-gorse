<?php

namespace Gorse\Model;

class Score
{
    public string $id;
    public float $score;

    public static function fromJSON($json): Score
    {
        $score = new Score();
        $score->id = $json->Id;
        $score->score = $json->Score;
        return $score;
    }
}
