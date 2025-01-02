<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Event\UserRegisteredEvent;

final class UserFixtures extends Fixture
{
    public const DEFAULT_USER_LOGIN = 'foo';

    public const DEFAULT_USER_PASSWORD = 'bar';

    public const USER_LOGIN_ROLE_BAR = 'bar';

    public const USER_PASSWORD_ROLE_BAR = 'foo';

    public const ADMIN_USER = 'admin';

    public const ADMIN_USER_PASSWORD = 'admin';

    public const BEGINNER_STUDENT_USER = 'beginner student';

    public const BEGINNER_STUDENT_PASSWORD = 'user';

    public const INTERMEDIATE_STUDENT_USER = 'intermediate student';

    public const INTERMEDIATE_STUDENT_PASSWORD = 'user';

    public const ADVANCED_STUDENT_USER = 'advanced student';

    public const ADVANCED_STUDENT_PASSWORD = 'user';

    /** @var EventDispatcherInterface */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    public function getDependencies()
    {
        return array('App\DataFixtures\RoleFixtures'); // fixture classes fixture is dependent on
    }
    public function load(ObjectManager $manager): void
    {
        $this->createUser($manager, self::ADMIN_USER, self::ADMIN_USER_PASSWORD);
        $this->createUser($manager, self::DEFAULT_USER_LOGIN, self::DEFAULT_USER_PASSWORD);
        $this->createUser($manager, self::USER_LOGIN_ROLE_BAR, self::USER_PASSWORD_ROLE_BAR);
        $this->createUser($manager, self::BEGINNER_STUDENT_USER, self::BEGINNER_STUDENT_PASSWORD);
        $this->createUser($manager, self::INTERMEDIATE_STUDENT_USER, self::INTERMEDIATE_STUDENT_PASSWORD);
        $this->createUser($manager, self::ADVANCED_STUDENT_USER, self::ADVANCED_STUDENT_PASSWORD);
    }

    /**
     * @param string[] $roles
     */
    private function createUser(ObjectManager $manager, string $login, string $password): void
    {
        $userEntity = new User();
        $userEntity->setLogin($login);
        $userEntity->setPlainPassword($password);
        $manager->persist($userEntity);
        $userEntityId = $userEntity->getId();

        $event = new UserRegisteredEvent($userEntity);
        $this->dispatcher->dispatch($event,UserRegisteredEvent::NAME);

        $manager->flush();
    }
}
