<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminType;
use App\Services\Ai\AiService;
use App\Repository\UserRepository;
use App\Repository\OutfitRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
final class AdminController extends AbstractController
{
    #[Route(name: 'app_admin_index', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository, OutfitRepository $outfitRepository, ItemRepository $itemRepository, AiService $aiService): Response
    {
        $users = $userRepository->findAll();
        $outfits = $outfitRepository->findAll();
        $items = $itemRepository->findAll();
        $notifications = [];

        foreach ($items as $item) {
            if ($item->getBrand() === 'N/A' || $item->getBrand() === 'Unknown' || $item->getBrand() === 'unknown' || empty($item->getBrand())) {
                $notifications[] = $aiService->generateSuggestionAdminDashboard("marque", "item", $item->getId());
            }
            if ($item->getColor() === 'N/A' || $item->getColor() === 'Unknown' || $item->getColor() === 'unknown' || empty($item->getColor())) {
                $notifications[] = $aiService->generateSuggestionAdminDashboard("couleur", "item", $item->getId());
            }
            if ($item->getType() === 'N/A' || $item->getType() === 'Unknown' || $item->getType() === 'unknown' || empty($item->getType())) {
                $notifications[] = $aiService->generateSuggestionAdminDashboard("type", "item", $item->getId());
            }
            if ($item->getFit() === 'N/A' || $item->getFit() === 'Unknown' || $item->getFit() === 'unknown' || empty($item->getFit())) {
                $notifications[] = $aiService->generateSuggestionAdminDashboard("fit", "item", $item->getId());
            }
            if ($item->getMaterial() === 'N/A' || $item->getMaterial() === 'Unknown' || $item->getMaterial() === 'unknown' || empty($item->getMaterial())) {
                $notifications[] = $aiService->generateSuggestionAdminDashboard("material", "item", $item->getId());
            }
        }

        $session = $request->getSession();
        $session->set('admin_notifications', $notifications);

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'outfits' => $outfits,
            'items' => $items,
            'notifications' => $notifications,
        ]);
    }

    #[Route('/new', name: 'app_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
