<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);

            $this->addReference('category_' . $i, $category);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
