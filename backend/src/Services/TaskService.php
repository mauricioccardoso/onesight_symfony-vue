<?php

namespace App\Services;

use App\Entity\Task;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TaskService
{
    public function __construct (
        private readonly EntityManagerInterface $entityManager,
        private readonly SecurityService        $securityService,
    ) {}

    public function list (): Collection
    {
        return $this->securityService->getAuthUser()->getTasks();
    }

    public function create ($taskData)
    {
        $user = $this->securityService->getAuthUser();

        $task = new Task();
        $task->setName($taskData->name);
        $task->setUser($user);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function update ($taskData, $taskId): Task
    {
        $user = $this->securityService->getAuthUser();

        $task = $this->findById($taskId);
        $this->verifyUserPermission($user, $task);

        $task->setName($taskData->name);
        $task->setCompleted($taskData->completed);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function delete ($taskId): void
    {
        $user = $this->securityService->getAuthUser();

        $task = $this->findById($taskId);

        $this->verifyUserPermission($user, $task);

        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function findById ($taskId): ?Task
    {
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);

        if (!$task) {
            throw new \Error('Task not exists');
        }

        return $task;
    }

    public function verifyUserPermission ($user, $task): void
    {
        if (!$user->getTasks()->contains($task)) {
            throw new Exception('Invalid permission');
        }
    }
}