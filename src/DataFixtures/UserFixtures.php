<?php

namespace App\DataFixtures;

use App\Entity\Gallery;
use App\Entity\Score;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Create demo users
        $users = [
            [
                'email' => 'demo@example.com',
                'username' => 'demo_user',
                'password' => 'password123',
            ],
            [
                'email' => 'test@example.com',
                'username' => 'test_user',
                'password' => 'testpass456',
            ],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setUsername($userData['username']);
            $user->setPassword($this->hasher->hashPassword($user, $userData['password']));
            $user->setRoles(['ROLE_USER']);

            // Add some demo gallery items
            for ($i = 1; $i <= 3; $i++) {
                $gallery = new Gallery();
                $gallery->setUser($user);
                $gallery->setTitle('Image de Demo ' . $i);
                $gallery->setImagePath('demo-' . $i . '.jpg');
                $gallery->setCreatedAt(new DateTimeImmutable('-' . $i . ' weeks'));
                $manager->persist($gallery);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}
