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
    public function __construct (
        private readonly TaskService     $taskService,
        private readonly SecurityService $securityService
    ) {}

    #[Route('/tasks', name: 'task_list', methods: 'GET')]
    public function index (): JsonResponse
    {
        $tasks = $this->taskService->list();

        return $this->json([
            'tasks' => $tasks
        ]);
    }

    #[Route('/tasks', name: 'task_create', methods: 'POST')]
    public function create (Request $request)
    {
        $requestData = json_decode($request->getContent());

        $task = $this->taskService->create($requestData);

        return $this->json([
            'tasks' => $task
        ], 201);
    }

    #[Route('/tasks/{id}', name: 'task_update', methods: 'PUT')]
    public function update (Request $request, int $id): JsonResponse
    {
        $requestData = json_decode($request->getContent());

        $taskUpdated = $this->taskService->update($requestData, $id);

        return $this->json([
            'tasks' => $taskUpdated
        ]);
    }

    #[Route('/tasks/{id}', name: 'task_delete', methods: 'DELETE')]
    public function delete (int $id): JsonResponse
    {
        $this->taskService->delete($id);

        return $this->json([], 204);
    }
}
