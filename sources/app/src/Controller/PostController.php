<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\Video;
use App\Entity\VideoRole;
use App\Entity\Post;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @Rest\Route("/api")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class PostController extends AbstractController
{
    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var EntityManagerInterface */
    private $em;

    /** @var SerializerInterface */
    private $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, EventDispatcherInterface $dispatcher)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @throws BadRequestHttpException
     *
     * @Rest\Post("/posts", name="createPost")
     * @IsGranted("ROLE_FOO")
     */
    public function createAction(Request $request): JsonResponse
    {
        $message = $request->request->get('message');
        if (empty($message)) {
            throw new BadRequestHttpException('message cannot be empty');
        }
        $post = new Post();
        $post->setMessage($message);
        $this->em->persist($post);
        $this->em->flush();
        $data = $this->serializer->serialize($post, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Rest\Get("/posts", name="findAllPosts")
     */
    public function findAllAction(): JsonResponse
    {
        $posts = $this->em->getRepository(Post::class)->findBy([], ['id' => 'DESC']);
        $data = $this->serializer->serialize($posts, JsonEncoder::FORMAT);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    /**
     * @Rest\Get("/get-videos", name="getVideo")
     */
    public function getVideos(Security $security): JsonResponse
    {
        try {

            $user = $security->getUser();
            $filteredVideos = $this->em->getRepository(Video::class)->findByUserId($this->em, $user->getId()->toString());
            $data = $this->serializer->serialize($filteredVideos, JsonEncoder::FORMAT, ['groups' => ['category:read']]);

            return new JsonResponse($data, Response::HTTP_OK, [], true);

        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    /**
     * @Rest\Post("/upload-video", name="uploadVideo")
     */
    public function uploadVideo(Request $request)
    {

        $categoryId = $request->request->get('categoryId');
        $roleId = $request->request->get('roleId');

        $video = new Video();

        try {
            $category = $this->em->getRepository(Category::class)->find($categoryId);
            $role = $this->em->getRepository(Role::class)->find($roleId);

            $video->setCategory($category);

            $videoFile = $request->files->get('video');

            if ($videoFile) {
                $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $videoFile->guessExtension();

                $videoFile->move(
                    $this->getParameter('video_directory'), // Define the directory where videos should be stored
                    $newFilename
                );

                $video->setVideoFilePath($newFilename);

                $videoRole = new VideoRole();
                $videoRole->setRole($role);
                $videoRole->setVideo($video);

                $video->getVideoRoles()->add($videoRole);
                $this->em->persist($video);
                $this->em->flush();
            }

            $responseData = [
                "message" => "You have successfully uploaded the video",
                "video" => $video
            ];

            $data = $this->serializer->serialize($responseData, JsonEncoder::FORMAT, ['groups' => ['video:read']]);

            return new JsonResponse($data, Response::HTTP_OK, [], true);

        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    /**
     * @Rest\Get("/get-categories", name="getCategories")
     */
    public function getCategories()
    {
        // $categories = $this->em->getRepository(Category::class)->findAll();
        $currentLoggedInUserId = $this->getUser()->getId();

        // Query to fetch categories with their associated filtered videos
        $query = $this->em->createQuery(
            'SELECT c, v
     FROM App\Entity\Category c
     JOIN c.videos v
     JOIN v.videoRoles vr
     JOIN vr.role r
     JOIN App\Entity\UserRole ur
     WHERE ur.user = :userId
     AND ur.role = r.id
     AND v.category = c
     ORDER BY c.id, v.id'
        );

        $query->setParameter('userId', $currentLoggedInUserId);

        $categoriesWithFilteredVideos = $query->getResult();
        $categoriesWithFilteredVideosCollection = new ArrayCollection($categoriesWithFilteredVideos);

        $categories = $this->em->getRepository(Category::class)->findAll();

        foreach ($categories as $category) {
            foreach ($categoriesWithFilteredVideos as $filteredCategory) {
                if (!$categoriesWithFilteredVideosCollection->contains($category)) {
                    $category->setVideos(new ArrayCollection());
                }

                if ($category->getName() === $filteredCategory->getName()) {
                    $category->setVideos($filteredCategory->getVideos());
                    break;
                }
            }

        }

        $categoriesWithFilteredVideos = $categories;

        try {
            $categories = $this->em->getRepository(Category::class)->findAll();
            $data = $this->serializer->serialize($categoriesWithFilteredVideos, JsonEncoder::FORMAT, ['groups' => ['category:read']]);

            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Get("/get-users", name="getUsers")
     */
    public function getUsers()
    {
        try {
            $users = $this->em->getRepository(User::class)->findAll();
            $data = $this->serializer->serialize($users, JsonEncoder::FORMAT, ['groups' => ['user:read']]);

            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    /**
     * @Rest\Get("/get-all-roles", name="getAllRoles")
     */
    public function getAllRoles()
    {

        try {
            $roles = $this->em->getRepository(Role::class)->findAll();
            $data = $this->serializer->serialize($roles, JsonEncoder::FORMAT, ['groups' => ['role:read']]);
            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Delete("/delete-video/{id}", name="deleteVideo")
     */
    public function deleteVideo($id)
    {

        try {
            $videoToDelete = $this->em->getRepository(Video::class)->find($id);
            $videoFilePath = $videoToDelete->getVideoFilePath();
            $videoPath = $this->getParameter('kernel.project_dir') . '/public/uploads/videos/' . $videoFilePath;
            unlink($videoPath);

            if (!$videoToDelete) {
                throw $this->createNotFoundException('Video not found.');
            }

            $this->em->remove(/*  */ $videoToDelete);

            $this->em->flush();

            return new JsonResponse('succesfully deleted!', Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Get("/edit-video/{id}", name="editVideo")
     */
    public function editVideo($id)
    {
        try {
            $videoToEdit = $this->em->getRepository(Video::class)->find($id);
            $data = $this->serializer->serialize($videoToEdit, JsonEncoder::FORMAT, ['groups' => ['video:read']]);

            if (!$videoToEdit) {
                throw $this->createNotFoundException('Video not found.');
            }
            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    /**
     * @Rest\Post("/update-video/{id}", name="updateVideo")
     */
    public function updateVideo($id, Request $request)
    {

        try {

            $videoToUpdate = $this->em->getRepository(Video::class)->find($id);
            $categoryId = $request->request->get('categoryId');
            $cateogryToUpdateToVideo = $this->em->getRepository(Category::class)->find($categoryId);

            if (!$videoToUpdate) {
                throw $this->createNotFoundException('Video not found.');
            }

            $videoToUpdate->setVideoFilePath($request->request->get('videoFilePath'));
            $videoToUpdate->setCategory($cateogryToUpdateToVideo);

            $this->em->persist($videoToUpdate);
            $this->em->flush();

            $responseData = [
                "message" => "You have successfully updated the video",
                "video" => $videoToUpdate
            ];

            $data = $this->serializer->serialize($responseData, JsonEncoder::FORMAT, ['groups' => ['video:read']]);

            return new JsonResponse($data, Response::HTTP_OK, [], true);

        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Delete("/delete-category/{id}", name="deleteCategory")
     */
    public function deleteCategory($id)
    {
        try {
            $categoryToDelete = $this->em->getRepository(Category::class)->find($id);

            if (!$categoryToDelete) {
                throw $this->createNotFoundException('Cateogry not found.');
            }

            $this->em->remove($categoryToDelete);

            $this->em->flush();

            return new JsonResponse('succesfully deleted!', Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }
    }


    /**
     * @Rest\Get("/edit-category/{id}", name="editcategory")
     */
    public function editCategory($id)
    {
        try {
            $categoryToEdit = $this->em->getRepository(Category::class)->find($id);
            $data = $this->serializer->serialize($categoryToEdit, JsonEncoder::FORMAT,['groups' => ['category:read']]);

            if (!$categoryToEdit) {
                throw $this->createNotFoundException('Video not found.');
            }
            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Post("/update-category/{id}", name="updateCategory")
     */
    public function updateCategory($id, Request $request)
    {
        try {
            $categoryToUpdate = $this->em->getRepository(Category::class)->find($id);

            if (!$categoryToUpdate) {
                throw $this->createNotFoundException('Category not found.');
            }

            $categoryToUpdate->setName($request->request->get('categoryName'));

            $this->em->persist($categoryToUpdate);
            $this->em->flush();
            $responseData = [
                "message" => "You have successfully updated the category",
                "category" => $categoryToUpdate
            ];
            $data = $this->serializer->serialize($responseData, JsonEncoder::FORMAT, ['groups' => ['category:read']]);

            return new JsonResponse($data, Response::HTTP_OK, [], true);

        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Delete("/delete-user/{id}", name="deleteUser")
     */
    public function deleteUser($id)
    {
        try {
            $userToDelete = $this->em->getRepository(User::class)->find($id);

            if (!$userToDelete) {
                throw $this->createNotFoundException('Cateogry not found.');
            }

            $this->em->remove($userToDelete);

            $this->em->flush();

            return new JsonResponse('succesfully deleted!', Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }
    }


    /**
     * @Rest\Get("/edit-user/{id}", name="editUser")
     */
    public function editUser($id)
    {

        try {
            $userToEdit = $this->em->getRepository(User::class)->find($id);
            $data = $this->serializer->serialize($userToEdit, JsonEncoder::FORMAT,['groups' => ['user:read']]);

            if (!$userToEdit) {
                throw $this->createNotFoundException('User not found.');
            }
            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Post("/update-user/{id}", name="updateUser")
     */
    public function updateUser($id, Request $request)
    {

        try {
            $userToUpdate = $this->em->getRepository(User::class)->find($id);
            $roleToUpdate = $this->em->getRepository(Role::class)->find($request->request->get('role'));

            if (!$userToUpdate) {
                throw $this->createNotFoundException('User not found.');
            }

            $userToUpdate->setLogin($request->request->get('userName'));
            $userToUpdate->getUserRoles()->first()->setRole($roleToUpdate);

            $this->em->persist($userToUpdate);
            $this->em->flush();

            $data = $this->serializer->serialize('you have successfully updated', JsonEncoder::FORMAT);

            return new JsonResponse($data, Response::HTTP_OK, [], true);

        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }

    /**
     * 
     *
     * @Rest\Post("/register", name="register")
     */
    public function register(Request $request): void
    {
        dd($request);
    }
    /**
     * @Rest\Get("/test", name="test")
     */
    public function test(Security $security)
    {
        //testing route
    }
}
