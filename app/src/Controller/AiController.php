<?php

namespace App\Controller;

use App\Services\Ai\AiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OutfitRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Outfit;
use App\Entity\Item;

class AiController extends AbstractController
{
    private $aiService;
    private $entityManager;

    public function __construct(AiService $aiService, EntityManagerInterface $entityManager)
    {
        $this->aiService = $aiService;
        $this->entityManager = $entityManager;
    }

    #[Route('/ai/suggestions', name: 'app_ai_suggestions')]
    public function generateSuggestions(Request $request, OutfitRepository $outfitRepository, ItemRepository $itemRepository): Response
    {
        $user = $this->getUser();
        $userOutfits = $outfitRepository->findBy(['userId' => $user]);
        $userWardrobe = $itemRepository->findBy(['userId' => $user]);

        $outfitData = array_map(function ($outfit) {
            return [
                'id' => $outfit->getId(),
                'name' => $outfit->getName(),
                'imageUrl' => $outfit->getImageUrl(),
                'likes' => $outfit->getLikes()->count(),
                'items' => array_map(function ($item) {
                    return [
                        'name' => $item->getName(),
                        'brand' => $item->getBrand(),
                        'color' => $item->getColor(),
                        'fit' => $item->getFit(),
                        'type' => $item->getType(),
                        'material' => $item->getMaterial()
                    ];
                }, $outfit->getItems()->toArray())
            ];
        }, $userOutfits);

        $wardrobeData = array_map(function ($item) {
            return [
                'name' => $item->getName(),
                'brand' => $item->getBrand(),
                'color' => $item->getColor(),
                'fit' => $item->getFit(),
                'type' => $item->getType(),
                'material' => $item->getMaterial()
            ];
        }, $userWardrobe);

        $userInput = $request->query->get('userInput', '');

        $suggestions = $this->aiService->generateOutfitSuggestionsWithUserInput($outfitData, $wardrobeData, $userInput);


        $logDir = __DIR__ . '/../../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        file_put_contents($logDir . '/suggestions.log', $suggestions);


        $suggestionsArray = json_decode($suggestions, true);
        dump($suggestionsArray);



        if ($suggestionsArray === null) {
            throw new \Exception('Failed to decode JSON: ' . json_last_error_msg() . "\n" . $suggestions);
        }

        return $this->render('outfit/suggestion.html.twig', [
            'suggestions' => $suggestionsArray,
        ]);
    }

    #[Route('/ai/suggestions/add', name: 'app_ai_suggestions_add', methods: ['POST'])]
    public function addSuggestionToWardrobe(Request $request, OutfitRepository $outfitRepository, ItemRepository $itemRepository): Response
    {
        $user = $this->getUser();
        $suggestion = json_decode($request->getContent(), true);

        if (!$suggestion) {
            return new Response('Invalid suggestion data', Response::HTTP_BAD_REQUEST);
        }

        if (!isset($suggestion['name']) || !isset($suggestion['items']) || !is_array($suggestion['items'])) {
            return new Response('Invalid suggestion structure', Response::HTTP_BAD_REQUEST);
        }

        $outfit = new Outfit();
        $outfit->setName($suggestion['name']);
        $outfit->setImageUrl($suggestion['imageUrl'] ?? '');
        $outfit->setIsPublic(false);
        $outfit->setAddAt(new \DateTimeImmutable());
        $outfit->setPromptResult($suggestion['promptResult'] ?? '');
        $outfit->setUserId($user);

        foreach ($suggestion['items'] as $itemData) {
            if (!isset($itemData['name'], $itemData['brand'], $itemData['color'], $itemData['fit'], $itemData['type'], $itemData['material'])) {
                return new Response('Invalid item structure', Response::HTTP_BAD_REQUEST);
            }

            $item = new Item();
            $item->setName($itemData['name']);
            $item->setBrand($itemData['brand']);
            $item->setColor($itemData['color']);
            $item->setFit($itemData['fit']);
            $item->setType($itemData['type']);
            $item->setMaterial($itemData['material']);
            $item->setUserId($user);

            $this->entityManager->persist($item);
            $outfit->addItem($item);
        }

        $this->entityManager->persist($outfit);
        $this->entityManager->flush();

        return new Response('Suggestion added to wardrobe', Response::HTTP_OK);
    }
}
