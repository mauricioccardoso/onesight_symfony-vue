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
        $userExists = $this->findByEmail($email);

        if ($userExists) {
            $exceptionData = new ServiceExceptionData(401, 'E-mail already registered');
            throw new ServiceException($exceptionData);
        }

        $this->passwordValidation($userData);

        $user = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $userData->password);

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

    public function findByEmail ($email): User | null
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }

    private function passwordValidation($userData): void
    {
        if($userData->password !== $userData->passwordConfirmation) {
            $exceptionData = new ServiceExceptionData(400, 'Passwords do not match');
            throw new ServiceException($exceptionData);
        }
    }
}