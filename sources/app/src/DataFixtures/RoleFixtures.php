<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class RoleFixtures extends Fixture
{
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_BEGINNER = 'ROLE_BEGINNER';
    public const ROLE_INTERMEDIATE = 'ROLE_INTERMEDIATE';
    public const ROLE_ADVANCED = 'ROLE_ADVANCED';

    public function load(ObjectManager $manager): void
    {
        $connection = $manager->getConnection();
        $connection->executeQuery('TRUNCATE TABLE roles');
        $this->createRole($manager, self::ROLE_ADMIN);
        $this->createRole($manager, self::ROLE_BEGINNER);
        $this->createRole($manager, self::ROLE_INTERMEDIATE);
        $this->createRole($manager, self::ROLE_ADVANCED);
    }

    /**
     * @param string[] $roles
     */
    private function createRole(ObjectManager $manager, string $name): void
    {
        $roleEntity = new Role();
        $roleEntity->setName($name);
        $manager->persist($roleEntity);
        $manager->flush();
    }
}
