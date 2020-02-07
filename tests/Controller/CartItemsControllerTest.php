<?php
// tests/Controller/CartItemsControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartItemsControllerTest extends WebTestCase
{
    /**
     * @dataProvider provider
     * @param $a
     * @param $b
     * @return array
     */
    public function testAddItemToCart(array $a, array $b)
    {
        $client = static::createClient();

        $client->request('POST', '/addCartItem', $a);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        return $b;
    }

    /*
     * @depends testAddItemToCart
     */
    public function testGetCartItems()
    {
        $client = static::createClient();

        $client->request('GET', '/Cart/1/items');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $expected = json_encode(func_get_args());

        $this->assertJson($expected, $client->getResponse()->getContent());
    }

    public function provider()
    {
        return array(
            array($_POST['item'] = [
                'name' => 'hello',
                'cost' => 123,
                'cart' => ''
            ], array(
                "id" => 1,
                "items" => array(
                    "id" => 1,
                    "name" => "hello",
                    "cost" => 123,
                    "cart" => 1
                )
            )),
            array($_POST['item'] = [
                'name' => 'world',
                'cost' => 456,
                'cart' => 1
            ], array(
                "id" => 1,
                "items" => array(
                    "id" => 1,
                    "name" => "hello",
                    "cost" => 123,
                    "cart" => 1
                ), array(
                    "id" => 1,
                    "name" => "world",
                    "cost" => 456,
                    "cart" => 1
                )
            ))
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}