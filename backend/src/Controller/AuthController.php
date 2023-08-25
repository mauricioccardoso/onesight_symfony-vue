<?php

namespace App\Controller;

use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    ) { }

    #[Route('/registration', name: 'api_registration', methods: 'POST')]
    public function index(Request $request): JsonResponse
    {
        $decodedData = json_decode($request->getContent());

        $this->userService->createUser($decodedData);

        return $this->json(['message' => 'Registered Successfully!']);
    }
}
