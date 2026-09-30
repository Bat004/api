<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$email, $firstName, $lastName, $createdAt]) {
            $user = new User();
            $user->setEmail($email);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setCreatedAt($createdAt);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'motdepasse')
            );

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return array<int, array{string, ?string, ?string, \DateTimeImmutable}>
     */
    private function getUserData(): array
    {
        return [
            ['alice@example.fr', null, null, new \DateTimeImmutable()],
            ['bob@example.fr', null, null, new \DateTimeImmutable()],
            ['camille.aubert@example.fr', 'Camille', 'Aubert', new \DateTimeImmutable('2026-02-04')],
        ];
    }
}
