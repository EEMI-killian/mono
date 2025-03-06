<?php

namespace App\Controller;

use App\Entity\Outfit;
use App\Entity\Item;
use App\Form\OutfitType;
use App\Form\ItemType;

use App\Repository\OutfitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Services\Ai\AiServiceInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Services\Image\ImageServiceInterface;

#[Route('/outfit')]
final class OutfitController extends AbstractController
{
    private AiServiceInterface $aiService;
    private ImageServiceInterface $imageService;

    public function __construct(AiServiceInterface $aiService, ImageServiceInterface $imageService)
    {
        $this->aiService = $aiService;
        $this->imageService = $imageService;
    }

    #[Route(name: 'app_outfit_index', methods: ['GET'])]
    public function index(OutfitRepository $outfitRepository): Response
    {
        return $this->render('outfit/index.html.twig', [
            'outfits' => $outfitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_outfit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $outfit = new Outfit();
        $form = $this->createForm(OutfitType::class, $outfit);
        $form->handleRequest($request);
        $aiResponse = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $uploadedFilePath = $this->imageService->uploadImage($imageFile);

                $outfit->setImageUrl('/uploads/images/' . $uploadedFilePath);

                $base64Image = $this->imageService->convertImageToBase64($uploadedFilePath);

                $aiResponse = $this->aiService->analyseImage($base64Image);
            }

            if ($aiResponse === null) {
                throw new \Exception('AI response is null');
            }

            $outfit->setPromptResult($aiResponse);
            $outfit->setAddAt(new \DateTimeImmutable());
            $outfit->setUserId($this->getUser());

            $data = json_decode($aiResponse, true);

            foreach ($data['items'] as $itemData) {
                $item = new Item();
                $item->setName($itemData['name'] ?? null);
                $item->setBrand($itemData['brand'] ?? null);
                $item->setColor($itemData['color'] ?? null);
                $item->setFit($itemData['fit'] ?? null);
                $item->setType($itemData['type'] ?? null);
                $item->setMaterial($itemData['material'] ?? null);
                $item->setUserId($this->getUser());


                $entityManager->persist($item);
                $outfit->addItem($item);
            }

            $entityManager->persist($outfit);
            $entityManager->flush();

            return $this->redirectToRoute('app_outfit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outfit/new.html.twig', [
            'outfit' => $outfit,
            'form' => $form,
            'aiResponse' => $aiResponse,
        ]);
    }


    #[Route('/{id}', name: 'app_outfit_show', methods: ['GET'])]
    public function show(Outfit $outfit): Response
    {
        return $this->render('outfit/show.html.twig', [
            'outfit' => $outfit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_outfit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Outfit $outfit, EntityManagerInterface $entityManager, UserInterface $user, SluggerInterface $slugger): Response
    {
        if ($outfit->getUserId() !== $user) {
            throw $this->createAccessDeniedException('You do not have permission to edit this outfit.');
        }

        $form = $this->createForm(OutfitType::class, $outfit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('File upload failed');
                }

                $outfit->setImageUrl('/uploads/images/' . $newFilename);

                $imagePath = $this->getParameter('images_directory') . '/' . $newFilename;
                $imageData = file_get_contents($imagePath);
                $base64Image = base64_encode($imageData);

                $aiResponse = $this->aiService->analyseImage($base64Image);

                if ($aiResponse === null) {
                    throw new \Exception('AI response is null');
                }

                $outfit->setPromptResult($aiResponse);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_outfit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outfit/edit.html.twig', [
            'outfit' => $outfit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/item/{itemId}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    public function editItem(Request $request, Outfit $outfit, Item $item, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        if ($outfit->getUserId() !== $user || $item->getUserId() !== $user) {
            throw $this->createAccessDeniedException('You do not have permission to edit this item.');
        }

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_outfit_show', ['id' => $outfit->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
            'outfit' => $outfit,
        ]);
    }

    #[Route('/{id}', name: 'app_outfit_delete', methods: ['POST'])]
    public function delete(Request $request, Outfit $outfit, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        if ($outfit->getUserId() !== $user) {
            throw $this->createAccessDeniedException('You do not have permission to delete this outfit.');
        }

        if ($this->isCsrfTokenValid('delete' . $outfit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($outfit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/outfit/{id}/add-item', name: 'app_outfit_add_item', methods: ['GET', 'POST'])]
    public function addItem(Request $request, Outfit $outfit, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        if ($outfit->getUserId() !== $user) {
            throw $this->createAccessDeniedException('You do not have permission to add items to this outfit.');
        }

        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item->setUserId($user);
            $outfit->addItem($item);
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('app_outfit_show', ['id' => $outfit->getId()]);
        }

        return $this->render('item/add_item.html.twig', [
            'outfit' => $outfit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/outfit/{id}/add-to-user', name: 'app_outfit_add_to_user', methods: ['POST'])]
    public function addToUser(Request $request, Outfit $outfit, EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $newOutfit = new Outfit();
        $newOutfit->setName($outfit->getName());
        $newOutfit->setImageUrl($outfit->getImageUrl());
        $newOutfit->setPromptResult($outfit->getPromptResult());
        $newOutfit->setAddAt(new \DateTimeImmutable());
        $newOutfit->setUserId($user);

        foreach ($outfit->getItems() as $item) {
            $newItem = new Item();
            $newItem->setName($item->getName());
            $newItem->setColor($item->getColor());
            $newItem->setBrand($item->getBrand());
            $newItem->setFit($item->getFit());
            $newItem->setType($item->getType()); // Assurez-vous que le champ type est copié
            $newItem->setMaterial($item->getMaterial()); // Assurez-vous que le champ material est copié
            $newItem->setUserId($user);
            $newOutfit->addItem($newItem);
            $entityManager->persist($newItem);
        }

        $entityManager->persist($newOutfit);
        $entityManager->flush();

        return $this->redirectToRoute('app_outfit_show', ['id' => $newOutfit->getId()]);
    }
}
