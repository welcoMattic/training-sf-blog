<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testCreatePost()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://localhost:8000/blog/post/create');

        $form = $crawler->selectButton('submit')->form();

        $crawler = $client->submit($form, [
            'post' => [
                'title' => 'Article de test',
                'body' => 'Lorem ipsum',
                'category' => 1,
                'is_published' => true,
            ]
        ]);

        $this->assertContains('Article créé avec succès !', $crawler->filter('.alert')->text());
    }
}
