<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Entity\UserRole;
use App\Entity\Role;

use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }
    public function onUserRegisteredEvent(UserRegisteredEvent $event)
    {
        //fixture logic

        $userLoginName = $event->getUser()->getLogin();
        // ...

        try {
            $connection = $this->em->getConnection();
            $userId =  (string)$event->getUser()->getId();
            // Build your insert query
            if ($userLoginName == 'admin') {
                $sql = "
                INSERT INTO user_role (user_id, role_id) VALUES
                (:userId, 1),
                (:userId, 2),
                (:userId, 3),
                (:userId, 4)
            ";
            $connection->executeStatement($sql, ['userId' => $userId]);

            } else if ($userLoginName == 'foo'
             || $userLoginName == 'beginner student')
            {
                $role = $this->em->getRepository(Role::class)->find(2);
                $userRole = new UserRole();
                $userRole->setUser($event->getUser());
                $userRole->setRole($role);
                $this->em->persist($userRole);
                $this->em->flush();
            } else if ($userLoginName == 'intermediate student'
             || $userLoginName == 'bar') {
                $role = $this->em->getRepository(Role::class)->find(3);
                $userRole = new UserRole();
                $userRole->setUser($event->getUser());
                $userRole->setRole($role);
                $this->em->persist($userRole);
                $this->em->flush();
                
            } else if ($userLoginName == 'advanced student') {
                $role = $this->em->getRepository(Role::class)->find(4);
                $userRole = new UserRole();
                $userRole->setUser($event->getUser());
                $userRole->setRole($role);
                $this->em->persist($userRole);
                $this->em->flush();
            }
            // Execute the query
            $connection->executeStatement($sql);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegisteredEvent'
        ];
    }
}
