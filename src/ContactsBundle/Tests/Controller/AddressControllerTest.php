<?php

namespace ContactsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddressControllerTest extends WebTestCase
{
    public function testNewaddress()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/address/new');
    }

    public function testModifyaddress()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/address/{id}/modify');
    }

    public function testDeleteaddress()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/address/{id}/delete');
    }

    public function testShowaddress()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/address/{id}');
    }

    public function testShowalladdress()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/address/');
    }

}
