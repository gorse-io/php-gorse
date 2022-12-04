<?php

use PHPUnit\Framework\TestCase;

final class GorseTest extends TestCase
{
    const ENDPOINT = "http://127.0.0.1:8088/";
    const API_KEY = "zhenghaoz";

    public function testUsers(): void
    {
        $client = new Gorse(self::ENDPOINT, self::API_KEY);
        $user = new User();
        $user->userId = "1";
        $user->labels = array("a", "b", "c");
        // Insert a user.
        $rowsAffected = $client->insertUser($user);
        $this->assertEquals(1, $rowsAffected->rowAffected);
    }
}
