<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Entity\UserRole;
use App\Entity\Role;
use Symfony\Component\HttpFoundation\Response;
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

        $userLoginName = $event->getUser()->getLogin();

        try {

            if ($userLoginName == 'admin') {

                $roleIds = [1, 2, 3, 4];

                $roles = $this->em->getRepository(Role::class)->findBy(['id' => $roleIds]);

                $userRoles = [];

                foreach ($roles as $role) {
                    $userRole = new UserRole();
                    $userRole->setUser($event->getUser());
                    $userRole->setRole($role);
                    $userRoles[] = $userRole;
                }

                foreach ($userRoles as $userRole) {
                    $this->em->persist($userRole);
                }

                $this->em->flush();

            } else if (
                $userLoginName == 'foo'
                || $userLoginName == 'beginner student'
            ) {
                $role = $this->em->getRepository(Role::class)->find(2);
                $userRole = new UserRole();
                $userRole->setUser($event->getUser());
                $userRole->setRole($role);
                $this->em->persist($userRole);
                $this->em->flush();
            } else if (
                $userLoginName == 'intermediate student'
                || $userLoginName == 'bar'
            ) {
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
