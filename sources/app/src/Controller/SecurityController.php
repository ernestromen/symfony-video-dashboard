<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Entity\Role;
use App\Entity\UserRole;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/api")
 */
final class SecurityController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    /**
     * @Route("/security/login", name="login")
     */
    public function loginAction(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $userClone = clone $user;
        $userClone->setPassword('');
        $data = $this->serializer->serialize($userClone, JsonEncoder::FORMAT, ['groups' => ['user:read']]);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @throws RuntimeException
     *
     * @Route("/security/logout", name="logout")
     */
    public function logoutAction(): void
    {
        throw new RuntimeException('This should not be reached!');
    }

    /**
     * @throws RuntimeException
     *
     * @Route("/security/register", name="register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $login = $request->request->get('login');
            $password = $request->request->get('password');

            $newUser = new User();
            $newUser->setLogin($login);
            $newUser->setPlainPassword($password);

            $role = $this->em->getRepository(Role::class)->find(2);

            $userRole = new UserRole();
            $userRole->setUser($newUser);
            $userRole->setRole($role);

            $newUser->getUserRoles()->add($userRole);

            $this->em->persist($newUser);
            $this->em->flush();

            $responseData = [
                "message" => "hello from 2000 years in the future",
                "user" => $newUser
            ];

            $data = $this->serializer->serialize($responseData, JsonEncoder::FORMAT, ['groups' => ['user:read']]);
            
            return new JsonResponse($data, Response::HTTP_OK, [], true);

        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
