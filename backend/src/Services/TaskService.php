<?php

namespace App\Services;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TaskService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) { }

    public function create($taskData, $user): Task
    {
        $task = new Task();
        $task->setUser($user);
        $task->setName($taskData->name);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function update($taskData, $taskId, $user): Task
    {
        $task = $this->verifyIfExistsAndPermission($taskId, $user);

        $task->setName($taskData->name);
        $task->setCompleted($taskData->completed);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function delete($taskId, $user): void
    {
        $task = $this->verifyIfExistsAndPermission($taskId, $user);

        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function findById($taskId): ?Task
    {
        return $this->entityManager->getRepository(Task::class)->find($taskId);
    }

    public function verifyIfExistsAndPermission($taskId, $user): Task
    {
        $task = $this->findById($taskId);

        if (!$task) {
            throw new Exception('Task not exists');
        }

        if ($task->getId() !== $user->getId()) {
            throw new Exception('Invalid permission');
        }
        return $task;
    }
}