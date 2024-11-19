<?php

declare(strict_types=1);

namespace App\Controller;
use App\Entity\Video;
use App\Entity\Post;
use App\Entity\Category;
use App\Entity\User;

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

/**
 * @Rest\Route("/api")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
final class PostController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var SerializerInterface */
    private $serializer;

        public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
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
    public function getVideos(): JsonResponse
    {
        try {
            $videos = $this->em->getRepository(Video::class)->findAll();
            // $category = $this->em->getRepository(Category::class)->findOneBy([]);

            $data = $this->serializer->serialize($videos, JsonEncoder::FORMAT);
            // $videos = $category->getVideos(); 
            // dd($videos->toArray());
            // getVideos
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

        $userId = $request->request->get('userId');
        $categoryId = $request->request->get('categoryId');
        $video = new Video();

        try {
            $category = $this->em->getRepository(Category::class)->find($categoryId);

            $video->setUserId($userId);
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
                $video->setVideoName($newFilename);

                // dd($video);
                $this->em->persist($video);
                $this->em->flush();
            }

            return new JsonResponse($newFilename, Response::HTTP_CREATED, [], true);

        } catch (\Exception $e) {
            return new Response($e->getMessage(), 500);
        }
    }

    /**
     * @Rest\Get("/get-categories", name="getCategories")
     */
    public function getCategories()
    {
        try {
            $categories = $this->em->getRepository(Category::class)->findAll();
            $data = $this->serializer->serialize($categories, JsonEncoder::FORMAT);

            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Get("/get-users", name="getUsers")
     */
    public function getUsers()
    {
        try {
            $users = $this->em->getRepository(User::class)->findAll();
            $data = $this->serializer->serialize($users, JsonEncoder::FORMAT);

            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Delete("/delete-video/{id}", name="deleteVideo")
     */
    public function deleteVideo($id)
    {
        try {
            $videoToDelete = $this->em->getRepository(Video::class)->find($id);

            if (!$videoToDelete) {
                throw $this->createNotFoundException('Video not found.');
            }

            $this->em->remove($videoToDelete);

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
            $data = $this->serializer->serialize($videoToEdit, JsonEncoder::FORMAT);

            if (!$videoToEdit) {
                throw $this->createNotFoundException('Video not found.');
            }
            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Post("/update-video/{id}", name="updateVideo")
     */
    public function updateVideo($id, Request $request)
    {
        try {
            $videoToUpdate = $this->em->getRepository(Video::class)->find($id);

            if (!$videoToUpdate) {
                throw $this->createNotFoundException('Video not found.');
            }
            
            $videoToUpdate->setVideoFilePath($request->request->get('videoFilePath'));

            $this->em->persist($videoToUpdate);
            $this->em->flush();

            $data = $this->serializer->serialize('you have successfully updated', JsonEncoder::FORMAT);

            return new JsonResponse($data, Response::HTTP_OK, [], true);

        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }


    //crud for categories


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
            $data = $this->serializer->serialize($categoryToEdit, JsonEncoder::FORMAT);

            if (!$categoryToEdit) {
                throw $this->createNotFoundException('Video not found.');
            }
            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
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

            $categoryToUpdate->setCategoryName($request->request->get('categoryName'));

            $this->em->persist($categoryToUpdate);
            $this->em->flush();

            $data = $this->serializer->serialize('you have successfully updated', JsonEncoder::FORMAT);

            return new JsonResponse($data, Response::HTTP_OK, [], true);

        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }



     //crud for user


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
            $data = $this->serializer->serialize($userToEdit, JsonEncoder::FORMAT);

            if (!$userToEdit) {
                throw $this->createNotFoundException('User not found.');
            }
            return new JsonResponse($data, Response::HTTP_OK, [], true);
        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }

    /**
     * @Rest\Post("/update-user/{id}", name="updateUser")
     */
    public function updateUser($id, Request $request)
    {

        try {
            $userToUpdate = $this->em->getRepository(User::class)->find($id);

            if (!$userToUpdate) {
                throw $this->createNotFoundException('User not found.');
            }

            $userToUpdate->setLogin($request->request->get('userName'));

            $this->em->persist($userToUpdate);
            $this->em->flush();

            $data = $this->serializer->serialize('you have successfully updated', JsonEncoder::FORMAT);

            return new JsonResponse($data, Response::HTTP_OK, [], true);

        } catch (\Exception $e) {
            echo new Response($e->getMessage(), 500);
        }

    }
}
