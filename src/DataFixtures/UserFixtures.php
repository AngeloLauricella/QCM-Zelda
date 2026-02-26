<?php

namespace App\DataFixtures;

use App\Entity\Gallery;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'email' => 'angelo_lauricella@icloud.com',
                'username' => 'Admin',
                'password' => 'Aragus2026',
                'roles' => ['ROLE_USER','ROLE_ADMIN'],
            ],
        ];

        foreach ($users as $userData) {

            $user = new User();
            $user->setEmail($userData['email']);
            $user->setUsername($userData['username']);
            $user->setRoles($userData['roles']);
            $user->setPassword(
                $this->hasher->hashPassword($user, $userData['password'])
            );


            $gallery = new Gallery();
            $gallery->setTitle('Galerie de ' . $user->getUsername()); // ✅ AJOUT
            $gallery->setCreatedAt(new DateTimeImmutable());
            $gallery->setUser($user);
            $gallery->setImagePath('mido.gif'); 

            $manager->persist($user);
            $manager->persist($gallery);
        }

        $manager->flush();
    }
}
