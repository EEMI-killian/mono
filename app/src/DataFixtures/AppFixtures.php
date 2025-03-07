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
            ['name' => 'Casquette', 'brand' => 'New Era', 'color' => 'blanc', 'type' => 'cap', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true],
            ['name' => 'Pull', 'brand' => 'Ami Paris', 'color' => 'bleu ciel', 'type' => 'top', 'fit' => 'oversize', 'material' => 'wool', 'isPublic' => true, 'partnerUrl' => 'https://northexclusive.fr/products/ami-paris-col-roule-alpaga-bleu'],
            ['name' => 'Pantalon', 'brand' => 'Ami Paris', 'color' => 'black', 'type' => 'bottom', 'fit' => 'oversize', 'material' => 'cotton', 'isPublic' => true,],
            ['name' => 'Chaussures', 'brand' => 'Nike', 'color' => 'bleu ciel', 'type' => 'shoes', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true, 'partnerUrl' => 'https://www.goat.com/fr-fr/sneakers/off-white-x-wmns-waffle-racer-cd8180-400'],

            ['name' => 'Veste', 'brand' => 'Bape', 'color' => 'bleu', 'type' => 'jacket', 'fit' => 'regular', 'material' => 'denim', 'isPublic' => true,],
            ['name' => 'T-shirt', 'brand' => 'Bape', 'color' => 'blanc', 'type' => 'top', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true,],
            ['name' => 'Short', 'brand' => 'Bape', 'color' => 'camouflage', 'type' => 'bottom', 'fit' => 'oversize', 'material' => 'cotton', 'isPublic' => true,],
            ['name' => 'Chaussures', 'brand' => 'Bape', 'color' => 'blanc', 'type' => 'shoes', 'fit' => 'regular', 'material' => 'leather', 'isPublic' => true, 'partnerUrl' => 'https://www.goat.com/fr-fr/sneakers/bapesta-abc-camo-green-2022-1i70191005-grn'],

            ['name' => 'Sweatshirt', 'brand' => 'Denim Tears', 'color' => 'rose', 'type' => 'top', 'fit' => 'oversize', 'material' => 'cotton', 'isPublic' => true, 'partnerUrl' => 'https://dimension-stores.com/products/301-100-30-pink'],
            ['name' => 'Survetement', 'brand' => 'Denim Tears', 'color' => 'rose', 'type' => 'bottom', 'fit' => 'oversize', 'material' => 'cotton', 'isPublic' => true, 'partnerUrl' => 'https://dimension-stores.com/products/401-100-30-pink'],
            ['name' => 'Chaussures', 'brand' => 'Nike', 'color' => 'blanc', 'type' => 'shoes', 'fit' => 'regular', 'material' => 'leather', 'isPublic' => true,],
            ['name' => 'Chaine', 'brand' => 'Live Yours', 'color' => 'gold', 'type' => 'accessories', 'fit' => 'regular', 'material' => 'metal', 'isPublic' => true,],

            ['name' => 'Varsity Jacket', 'brand' => 'Louis Vuitton', 'color' => 'blanc', 'type' => 'jacket', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true,],
            ['name' => 'Pantalon', 'brand' => 'Louis Vuitton', 'color' => 'bleu', 'type' => 'bottom', 'fit' => 'large', 'material' => 'denim', 'isPublic' => true,],
            ['name' => 'Chaussures', 'brand' => 'Nike', 'color' => 'white', 'type' => 'shoes', 'fit' => 'regular', 'material' => 'leather', 'isPublic' => true,],
            ['name' => 'Ceinture', 'brand' => 'Louis Vuitton', 'color' => 'argent', 'type' => 'accessories', 'fit' => 'regular', 'material' => 'metal', 'isPublic' => true,],

            ['name' => 'Veste', 'brand' => 'Tommy Hilfiger', 'color' => 'bleu', 'type' => 'jacket', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true,],
            ['name' => 'T-shirt', 'brand' => 'Tommy Hilfiger', 'color' => 'blanc', 'type' => 'top', 'fit' => 'slim', 'material' => 'cotton', 'isPublic' => true,],
            ['name' => 'Pantalon', 'brand' => 'Tommy Hilfiger', 'color' => 'bleu', 'type' => 'bottom', 'fit' => 'regular', 'material' => 'cotton', 'isPublic' => true,],
            ['name' => 'Chaussures', 'brand' => 'Nike', 'color' => 'blanc', 'type' => 'shoes', 'fit' => 'regular', 'material' => 'leather', 'isPublic' => true,],
        ];

        $createdItems = [];
        foreach ($items as $itemData) {
            $item = new Item();
            $item->setName($itemData['name']);
            $item->setBrand($itemData['brand']);
            $item->setColor($itemData['color']);
            $item->setType($itemData['type']);
            $item->setFit($itemData['fit']);
            $item->setMaterial($itemData['material']);
            $item->setIsPublic($itemData['isPublic']);
            if (isset($itemData['partnerUrl'])) {
                $item->setPartnerUrl($itemData['partnerUrl']);
            }
            $manager->persist($item);
            $createdItems[] = $item;
        }

        $outfits = [
            ['name' => 'Tenue AMI Paris', 'image' => '/images/ami.jpg', 'items' => [$createdItems[0], $createdItems[1], $createdItems[2], $createdItems[3]], 'isPublic' => true],
            ['name' => 'Tenue Bape', 'image' => '/images/bape.jpeg', 'items' => [$createdItems[4], $createdItems[5], $createdItems[6], $createdItems[7]], 'isPublic' => true],
            ['name' => 'Tenue Denim Tears', 'image' => '/images/denimtears.jpg', 'items' => [$createdItems[8], $createdItems[9], $createdItems[10], $createdItems[11]], 'isPublic' => true],
            ['name' => 'Tenue Louis Vuitton', 'image' => '/images/louisvuitton.jpg', 'items' => [$createdItems[12], $createdItems[13], $createdItems[14], $createdItems[15]], 'isPublic' => true],
            ['name' => 'Tenue Tommy Hilfiger', 'image' => '/images/tommy.jpg', 'items' => [$createdItems[16], $createdItems[17], $createdItems[18], $createdItems[19]], 'isPublic' => true],
        ];

        $createdOutfits = [];
        foreach ($outfits as $outfitData) {
            $outfit = new Outfit();
            $outfit->setName($outfitData['name']);
            $outfit->setImageUrl($outfitData['image']);
            $outfit->setAddAt(new \DateTimeImmutable());
            $outfit->setPromptResult($outfitData['name']);
            $outfit->setIsPublic($outfitData['isPublic']);
            foreach ($outfitData['items'] as $item) {
                $outfit->addItem($item);
            }
            $manager->persist($outfit);
            $createdOutfits[] = $outfit;
        }

        $users = [
            ['firstName' => 'Lucas', 'lastName' => 'Martin', 'email' => 'lucas.martin@example.com', 'outfits' => [$createdOutfits[0], $createdOutfits[1]], 'items' => [$createdItems[0], $createdItems[1],$createdItems[2], $createdItems[3],$createdItems[4], $createdItems[5], $createdItems[6], $createdItems[7]]],
            ['firstName' => 'Thomas', 'lastName' => 'Leroy', 'email' => 'thomas.leroy@example.com', 'outfits' => [$createdOutfits[2], $createdOutfits[3]], 'items' => [$createdItems[8], $createdItems[9], $createdItems[10], $createdItems[11], $createdItems[12], $createdItems[13], $createdItems[14], $createdItems[15]]],
            ['firstName' => 'Emma', 'lastName' => 'Dupont', 'email' => 'emma.dupont@example.com', 'outfits' => [$createdOutfits[4]], 'items' => [$createdItems[16], $createdItems[17], $createdItems[18], $createdItems[19]]],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);
            $user->setEmail($userData['email']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);

            foreach ($userData['outfits'] as $outfit) {
                $user->addOutfit($outfit);
            }
            foreach ($userData['items'] as $item) {
                $user->addItem($item);
            }
            
            $manager->persist($user);
        }

        $admin = new User();
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setEmail('admin@example.com');
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'adminpassword');
        $admin->setPassword($hashedPassword);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}
