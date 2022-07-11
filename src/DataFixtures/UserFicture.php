<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFicture extends Fixture
{
    protected $faker;
    protected $userRepository;
    protected $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository)
    {
        $this->faker = Factory::create();
        $this->userPasswordHasher = $userPasswordHasher;
        $this->userRepository = $userRepository;
    }
    public function load(ObjectManager $manager): void
    {

        // creating admin user
        $adminUser = new User();
        $adminUser->setEmail('markostevic96@hotmail.com');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setPassword(
            $this->userPasswordHasher->hashPassword(
                $adminUser,
                'test1234'
            )
        );
        $adminUser->setFirstName('Marko');
        $adminUser->setLastName('Stevic');

        $this->userRepository->add($adminUser, true);

        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $adminUser,
                    'test1234'
                )
            );
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);

            $this->userRepository->add($user, true);
        }
    }
}
