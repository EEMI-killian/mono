<?php

namespace App\Controller;

use App\Entity\Outfit;
use App\Form\OutfitType;
use App\Repository\OutfitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/outfit')]
final class OutfitController extends AbstractController
{
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

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }
                $outfit->setAddAt(new \DateTimeImmutable());

                $outfit->setImageUrl('/uploads/images/'.$newFilename);
            }

            $entityManager->persist($outfit);
            $entityManager->flush();

            // Appel à l'IA pour traiter l'image et créer les items
            // $this->processImageWithAI($outfit);

            return $this->redirectToRoute('app_outfit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outfit/new.html.twig', [
            'outfit' => $outfit,
            'form' => $form,
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
    public function edit(Request $request, Outfit $outfit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OutfitType::class, $outfit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_outfit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('outfit/edit.html.twig', [
            'outfit' => $outfit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_outfit_delete', methods: ['POST'])]
    public function delete(Request $request, Outfit $outfit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$outfit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($outfit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_outfit_index', [], Response::HTTP_SEE_OTHER);
    }
}
