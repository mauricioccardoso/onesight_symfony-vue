<?php

namespace App\Controller;

use App\Services\SecurityService;
use App\Services\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    public function __construct(
        private readonly SecurityService $securityService,
        private readonly TaskService $taskService
    ) { }

    #[Route('/tasks', name: 'task_list', methods: 'GET')]
    public function index(): JsonResponse
    {
        $user = $this->securityService->getAuthUser();

        $tasks = $user?->getTasks();

        return $this->json([
            'tasks' => $tasks
        ]);
    }

    #[Route('/tasks', name: 'task_create', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        $user = $this->securityService->getAuthUser();

        $decodedData = json_decode($request->getContent());

        $task = $this->taskService->create($decodedData, $user);

        return $this->json([
            'tasks' => $task
        ]);
    }
}
