<?php

namespace App\Services;

use Exception;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) { }

    public function createUser($userData): void
    {
        $email = $userData->email;
        $plainTextPassword = $userData->password;

        $userExists = $this->findByEmail($email);

        if($userExists) {
            throw new Exception('Email already registered');
        }

        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainTextPassword);

        $user->setEmail($email);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function findByEmail($email)
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    }
}