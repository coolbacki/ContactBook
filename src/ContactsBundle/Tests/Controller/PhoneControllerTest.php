<?php

namespace ContactsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    public function testNewphone()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/phone/new');
    }

    public function testModifyphone()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/phone/{id}/modify');
    }

    public function testDeletephone()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/phone/{id}/delete');
    }

    public function testShowphone()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/phone/{id}');
    }

    public function testShowallphone()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/phone/');
    }

}
