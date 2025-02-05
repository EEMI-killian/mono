<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Item;
use App\Entity\Outfit;

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\Outfit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $items = [
            ['name' => 'T-shirt nike', 'brand' => 'Nike', 'color' => 'black', 'type' => 'top'],
            ['name' => 'pants nike', 'brand' => 'Nike', 'color' => 'black', 'type' => 'pants'],
            ['name' => 'shoes nike', 'brand' => 'Nike', 'color' => 'black', 'type' => 'shoes'],
            ['name' => 'T-shirt adidas', 'brand' => 'Adidas', 'color' => 'white', 'type' => 'top'],
            ['name' => 'pants adidas', 'brand' => 'Adidas', 'color' => 'white', 'type' => 'pants'],
            ['name' => 'shoes adidas', 'brand' => 'Adidas', 'color' => 'white', 'type' => 'shoes'],
            ['name' => 'T-shirt puma', 'brand' => 'Puma', 'color' => 'red', 'type' => 'top'],
            ['name' => 'pants puma', 'brand' => 'Puma', 'color' => 'red', 'type' => 'pants'],
            ['name' => 'shoes puma', 'brand' => 'Puma', 'color' => 'red', 'type' => 'shoes'],
        ];

    
        $createdItems = [];
        foreach ($items as $itemData) {
            $newItem = new Item();
            $newItem->setName($itemData['name']);
            $newItem->setBrand($itemData['brand']);
            $newItem->setColor($itemData['color']);
            $newItem->setType($itemData['type']);
            $manager->persist($newItem);
            $createdItems[] = $newItem; 
        }

  
        $outfit = new Outfit();
        $outfit->setName('Sporty outfit');
        $outfit->setImageUrl('https://via.placeholder.com/150');
        $outfit->setAddAt(new \DateTimeImmutable());

     
        $outfit->addItem($createdItems[0]);
        $outfit->addItem($createdItems[1]);
        $outfit->addItem($createdItems[2]);

        $manager->persist($outfit);

        
        $manager->flush();
    }
}
