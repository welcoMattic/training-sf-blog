<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\PostTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $post = new Post();

            $postTranslationFr = new PostTranslation();
            $postTranslationFr->setTitle('Titre FR n°' . $i);
            $postTranslationFr->setBody('FR Lorem ipsum ' . $i);
            $postTranslationFr->setLocale('fr');

            $postTranslationEn = new PostTranslation();
            $postTranslationEn->setTitle('Titre EN n°' . $i);
            $postTranslationEn->setBody('EN Lorem ipsum ' . $i);
            $postTranslationEn->setLocale('en');

            $post->addTranslation($postTranslationFr);
            $post->addTranslation($postTranslationEn);

            $post->setIsPublished(mt_rand(0, 1));
            $post->setCategory($this->getReference('category_' . mt_rand(0, 2)));

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
