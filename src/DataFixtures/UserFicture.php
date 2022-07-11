<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFicture extends Fixture
{
    protected $faker;
    protected $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->faker = Factory::create();
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {

        // $product = new Product();
        // $manager->persist($product);
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

        $manager->persist($adminUser);
        $manager->flush();

        for ($i = 0; $i < 30; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email);
            $user->setRoles([]);
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $adminUser,
                    'test1234'
                )
            );
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);

            $manager->persist($user);
            $manager->flush();
        }
    }
}
