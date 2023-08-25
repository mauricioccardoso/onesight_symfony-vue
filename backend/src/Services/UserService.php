<?php

namespace App\Services;

use App\Services\Errors\ServiceException;
use App\Services\Errors\ServiceExceptionData;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct (
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function createUser ($userData): void
    {
        $email = $userData->email;
        $plainTextPassword = $userData->password;

        $userExists = $this->findByEmail($email);

        if ($userExists) {
            $exceptionData = new ServiceExceptionData(403, 'Access Denied');
            throw new ServiceException($exceptionData);
        }

        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainTextPassword);

        $this->entityManager->beginTransaction();
        try {
            $user->setEmail($email);
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            $exceptionData = new ServiceExceptionData($e->getCode(), $e->getMessage());
            throw new ServiceException($exceptionData);
        }
    }

    public function findByEmail ($email)
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }
}