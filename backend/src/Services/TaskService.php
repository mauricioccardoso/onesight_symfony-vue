<?php

namespace App\Services;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

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
}