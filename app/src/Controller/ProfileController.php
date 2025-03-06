<?php
namespace App\Controller;

use App\Entity\Outfit;
use App\Form\OutfitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Item;
use App\Form\ItemType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(UserInterface $user): Response
    {
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile/outfit/new', name: 'outfit_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $outfit = new Outfit();
        $form = $this->createForm(OutfitType::class, $outfit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $outfit->setUserId($user);
            foreach ($outfit->getItems() as $item) {
                $outfit->addItem($item);
            }
            $entityManager->persist($outfit);
            $entityManager->flush();

            return $this->redirectToRoute('app_social');
        }

        return $this->render('outfit/new2.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/item/edit/{id}', name: 'app_item_edit')]
    #[IsGranted('EDIT', subject: 'item')]
    public function editItem(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('item/edit.html.twig', [
            'form' => $form->createView(),
            'item' => $item,
        ]);
    }
    #[Route('/profile/liked-outfits', name: 'app_profile_liked_outfits', methods: ['GET'])]
    public function getLikedOutfits(UserInterface $user): Response
    {
        return $this->render('profile/_liked_outfits.html.twig', [
            'likes' => $user instanceof \App\Entity\User ? $user->getLikes() : [],
        ]);
    }
}