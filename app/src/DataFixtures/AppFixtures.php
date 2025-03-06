<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Item;
use App\Entity\Outfit;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $items = [
            ['name' => 'T-shirt nike', 'brand' => 'Nike', 'color' => 'black', 'type' => 'top', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
            ['name' => 'pants nike', 'brand' => 'Nike', 'color' => 'black', 'type' => 'pants', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
            ['name' => 'shoes nike', 'brand' => 'Nike', 'color' => 'black', 'type' => 'shoes', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
            ['name' => 'T-shirt adidas', 'brand' => 'Adidas', 'color' => 'white', 'type' => 'top', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
            ['name' => 'pants adidas', 'brand' => 'Adidas', 'color' => 'white', 'type' => 'pants', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
            ['name' => 'shoes adidas', 'brand' => 'Adidas', 'color' => 'white', 'type' => 'shoes', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
            ['name' => 'T-shirt puma', 'brand' => 'Puma', 'color' => 'red', 'type' => 'top', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
            ['name' => 'pants puma', 'brand' => 'Puma', 'color' => 'red', 'type' => 'pants', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
            ['name' => 'shoes puma', 'brand' => 'Puma', 'color' => 'red', 'type' => 'shoes', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
        ];

        $createdItems = [];
        foreach ($items as $itemData) {
            $newItem = new Item();
            $newItem->setName($itemData['name']);
            $newItem->setBrand($itemData['brand']);
            $newItem->setColor($itemData['color']);
            $newItem->setType($itemData['type']);
            $newItem->setFit($itemData['fit']);
            $newItem->setMaterial($itemData['material']);
            $newItem->setIsPublic($itemData['isPublic']);
            $manager->persist($newItem);
            $createdItems[] = $newItem;
        }

        $outfit = new Outfit();
        $outfit->setName('Sporty outfit');
        $outfit->setImageUrl('https://via.placeholder.com/150');
        $outfit->setAddAt(new \DateTimeImmutable());
        $outfit->setPromptResult("100% sporty outfit");
        $outfit->setIsPublic(true);

        $outfit->addItem($createdItems[0]);
        $outfit->addItem($createdItems[1]);
        $outfit->addItem($createdItems[2]);

        $manager->persist($outfit);
        $userData = [
            ['firstName' => 'John', 'lastName' => 'Doe', 'email' => 'john.doe@example.com'],
            ['firstName' => 'Jane', 'lastName' => 'Doe', 'email' => 'jane.doe@example.com'],
            ['firstName' => 'Jim', 'lastName' => 'Beam', 'email' => 'jim.beam@example.com'],
            ['firstName' => 'Jack', 'lastName' => 'Daniels', 'email' => 'jack.daniels@example.com'],
            ['firstName' => 'Jill', 'lastName' => 'Valentine', 'email' => 'jill.valentine@example.com'],
        ];

        foreach ($userData as $index => $data) {
            $user = new User();
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);

            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);

            $userOutfit = new Outfit();
            $userOutfit->setName('Outfit ' . ($index + 1));
            $userOutfit->setImageUrl('https://via.placeholder.com/150');
            $userOutfit->setAddAt(new \DateTimeImmutable());
            $userOutfit->setPromptResult("Outfit " . ($index + 1) . " description");
            $userOutfit->setIsPublic($index % 2 == 0); // Alternating public/private outfits



            $userOutfit->addItem($createdItems[$index * 2 % count($createdItems)]);
            $userOutfit->addItem($createdItems[($index * 2 + 1) % count($createdItems)]);

            $manager->persist($userOutfit);

            $user->addOutfit($userOutfit);
            $user->addItem($createdItems[($index * 2 + 2) % count($createdItems)]);
            $user->addItem($createdItems[($index * 2 + 3) % count($createdItems)]);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
