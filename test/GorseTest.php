<?php

use Gorse\Gorse;
use Gorse\Model\Feedback;
use Gorse\Model\Item;
use Gorse\Model\User;
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

        $user = new User("1000", array("M", "engineer"), "zhenghaoz");
        $rowsAffected = $client->insertUser($user);
        $this->assertEquals(1, $rowsAffected->rowAffected);
        $returnUser = $client->getUser("1000");
        $this->assertEquals($user, $returnUser);

        $rowsAffected = $client->deleteUser("1000");
        $this->assertEquals(1, $rowsAffected->rowAffected);
        try {
            $client->getUser("1000");
            $this->fail();
        } catch (ClientException $exception) {
            $this->assertEquals(404, $exception->getCode());
        }
    }

    /**
     * @throws GuzzleException
     */
    public function testItems()
    {
        $client = new Gorse(self::ENDPOINT, self::API_KEY);

        $item = new Item("2000", true, array("Comedy", "Animation"), "2022-11-20T13:55:27Z", array("comedy", "movie"), "Minions (2015)");
        $rowsAffected = $client->insertItem($item);
        $this->assertEquals(1, $rowsAffected->rowAffected);
        $returnItem = $client->getItem("2000");
        $this->assertEquals($item, $returnItem);

        $rowsAffected = $client->deleteItem("2000");
        $this->assertEquals(1, $rowsAffected->rowAffected);
        try {
            $client->getItem("2000");
            $this->fail();
        } catch (ClientException $exception) {
            $this->assertEquals(404, $exception->getCode());
        }
    }

    /**
     * @throws GuzzleException
     */
    public function testFeedback()
    {
        $client = new Gorse(self::ENDPOINT, self::API_KEY);

        $feedback = array(
            new Feedback("watch", "2000", "1", gmdate("Y-m-d\TH:i:s\Z"), 1.0),
            new Feedback("watch", "2000", "1060", gmdate("Y-m-d\TH:i:s\Z"), 2.0),
            new Feedback("watch", "2000", "11", gmdate("Y-m-d\TH:i:s\Z"), 3.0),
        );
        foreach ($feedback as $fb) {
            $client->deleteFeedback($fb->feedback_type, $fb->user_id, $fb->item_id);
        }
        $rowsAffected = $client->insertFeedback($feedback);
        $this->assertEquals(3, $rowsAffected->rowAffected);
    }

    /**
     * @throws GuzzleException
     */
    public function testRecommend()
    {
        $client = new Gorse(self::ENDPOINT, self::API_KEY);
        $client->insertUser(new User("3000", array(), ""));
        $items = $client->getRecommend('3000', null, null, 3);
        $this->assertIsArray($items);
    }

    /**
     * @throws GuzzleException
     */
    public function testNonPersonalized()
    {
        $client = new Gorse(self::ENDPOINT, self::API_KEY);
        // Test getLatest which returns Score[]
        $items = $client->getLatest('3000', 3);
        $this->assertIsArray($items);
        foreach ($items as $item) {
             $this->assertInstanceOf(\Gorse\Model\Score::class, $item);
        }
    }
}
