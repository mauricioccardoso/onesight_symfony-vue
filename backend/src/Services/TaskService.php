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

    public function findById($taskId)
    {
        return $this->entityManager->getRepository(Task::class)->find($taskId);
    }

    public function update($taskData, $taskId, $user): Task
    {
        $task = $this->findById($taskId);

        if(!$task) {
            throw new Exception('Task not exists');
        }

        if ($task->getId() !== $user->getId())
        {
            throw new Exception('Invalid permission');
        }

        $task->setName($taskData->name);
        $task->setCompleted($taskData->completed);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }
}