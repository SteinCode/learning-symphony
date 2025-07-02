<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->SetName("Koldunai");
        $product->setDescription("labai skanus");
        $product->setSize(200);

        $manager->persist($product);

        $product1 = new Product();
        $product1->SetName("Arbuzas");
        $product1->setDescription("Dar ner saldus");
        $product1->setSize(300);
        
        $manager->persist($product1);

        $manager->flush();
    }
}
 