<?php

namespace ContactsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmailControllerTest extends WebTestCase
{
    public function testNewemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/email/new');
    }

    public function testModifyemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/email/{id}/modify');
    }

    public function testDeleteemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/email/{id}/delete');
    }

    public function testShowemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/email/{id}');
    }

    public function testShowallemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/email');
    }

}
