<?php

namespace App\Services;

use App\Entity\Task;
use App\Services\Errors\ServiceException;
use App\Services\Errors\ServiceExceptionData;
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

        $this->entityManager->beginTransaction();
        try {
            $task = new Task();
            $task->setName($taskData->name);
            $task->setUser($user);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            $exceptionData = new ServiceExceptionData($e->getCode(), $e->getMessage());
            throw new ServiceException($exceptionData);
        }

        return $task;
    }

    public function update ($taskData, $taskId): Task
    {
        $user = $this->securityService->getAuthUser();

        $task = $this->findById($taskId);
        $this->verifyUserPermission($user, $task);

        $this->entityManager->beginTransaction();
        try {
            $task->setName($taskData->name);
            $task->setCompleted($taskData->completed);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            $exceptionData = new ServiceExceptionData($e->getCode(), $e->getMessage());
            throw new ServiceException($exceptionData);
        }


        return $task;
    }

    public function delete ($taskId): void
    {
        $user = $this->securityService->getAuthUser();

        $task = $this->findById($taskId);
        $this->verifyUserPermission($user, $task);

        $this->entityManager->beginTransaction();
        try {
            $this->entityManager->remove($task);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            $exceptionData = new ServiceExceptionData($e->getCode(), $e->getMessage());
            throw new ServiceException($exceptionData);
        }
    }

    public function findById ($taskId): ?Task
    {
        $task = $this->entityManager->getRepository(Task::class)->find($taskId);

        if (!$task) {
            $exceptionData = new ServiceExceptionData(404, 'Task Not Found');
            throw new ServiceException($exceptionData);
        }

        return $task;
    }

    public function verifyUserPermission ($user, $task): void
    {
        if (!$user->getTasks()->contains($task)) {
            $exceptionData = new ServiceExceptionData(401, 'Access Denied');
            throw new ServiceException($exceptionData);
        }
    }
}