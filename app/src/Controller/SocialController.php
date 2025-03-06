<?php
namespace App\Controller;

use App\Repository\OutfitRepository;
use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

final class SocialController extends AbstractController
{
    private $outfitRepository;
    private $itemRepository;

    public function __construct(OutfitRepository $outfitRepository, ItemRepository $itemRepository)
    {
        $this->outfitRepository = $outfitRepository;
        $this->itemRepository = $itemRepository;
    }

    #[Route('/social', name: 'app_social')]
    public function index(UserInterface $user): Response
    {
        $publicOutfits = $this->outfitRepository->findBy(['isPublic' => true]);
        $publicItems = $this->itemRepository->findBy(['isPublic' => true]);

        return $this->render('social/index.html.twig', [
            'outfits' => $publicOutfits,
            'items' => $publicItems,
            'user' => $user,
        ]);
    }
}