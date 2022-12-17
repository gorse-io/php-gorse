<?php

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

final class GorseTest extends TestCase
{
    const ENDPOINT = "http://127.0.0.1:8088/";
    const API_KEY = "zhenghaoz";

    /**
     * @throws GuzzleException
     */
    public function testUsers(): void
    {
        $client = new Gorse(self::ENDPOINT, self::API_KEY);
        $user = new User();
        $user->userId = "1";
        $user->labels = array("a", "b", "c");
        // Insert a user.
        $rowsAffected = $client->insertUser($user);
        $this->assertEquals(1, $rowsAffected->rowAffected);
        // Get this user.
        $returnUser = $client->getUser("1");
        $this->assertEquals($user, $returnUser);
        // Delete this user.
        $rowsAffected = $client->deleteUser("1");
        $this->assertEquals(1, $rowsAffected->rowAffected);
        try {
            $client->getUser("1");
            $this->fail();
        } catch (ClientException $exception) {
            $this->assertEquals(404, $exception->getCode());
        }
    }
}
