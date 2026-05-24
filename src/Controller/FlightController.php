<?php

namespace App\Controller;

use App\Service\AviationstackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class FlightController extends AbstractController
{
    #[Route('/test-vol', name: 'app_flight_test')]
    public function index(AviationstackService $aviationstackService): JsonResponse
    {
        $flights = $aviationstackService->searchFlight('Londres', 'Paris', '2026-03-17');

        return $this->json($flights);
    }
}
