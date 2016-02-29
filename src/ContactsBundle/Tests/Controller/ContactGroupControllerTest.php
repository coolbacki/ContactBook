<?php

namespace ContactsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactGroupControllerTest extends WebTestCase
{
    public function testNewcontactgroup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contactGroup/new');
    }

    public function testModifycontactgroup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contactGroup/{id}/modify');
    }

    public function testDeletecontactgroup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contactGroup/{id}/delete');
    }

    public function testShowcontactgroup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contactGroup/{id}');
    }

    public function testShowallcontactgroup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/contactGroup');
    }

}
