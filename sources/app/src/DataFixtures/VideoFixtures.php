<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Video;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\VideoRole;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Doctrine\ORM\EntityManagerInterface;

final class VideoFixtures extends Fixture
{
    public const FIRST_VIDEO_PATH = 'file-678a9fabb0f99.mp4';

    public const SECOND_VIDEO_PATH = 'file-678a9fe736c7c.mp4';

    public const THIRD_VIDEO_PATH = 'file-6789a5f1e0e23.mp4';

    public const FIRST_VIDEO_CATEGORY = 1;

    public const SECOND_VIDEO_CATEGORY = 2;

    public const THIRD_VIDEO_CATEGORY = 3;

    public const FIRST_VIDEO_ROLE = 1;
    public const SECOND_VIDEO_ROLE = 2;
    public const THIRD_VIDEO_ROLE = 3;


    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var EntityManagerInterface */
    private $em;
    public function __construct(EventDispatcherInterface $dispatcher, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }
    public function getDependencies()
    {
        return array('App\DataFixtures\RoleFixtures'); // fixture classes fixture is dependent on
    }
    public function load(ObjectManager $manager): void
    {
        $this->createVideo($manager, self::FIRST_VIDEO_CATEGORY, self::FIRST_VIDEO_PATH,self::FIRST_VIDEO_ROLE);
        $this->createVideo($manager, self::SECOND_VIDEO_CATEGORY, self::SECOND_VIDEO_PATH,self::SECOND_VIDEO_ROLE);
        $this->createVideo($manager, self::THIRD_VIDEO_CATEGORY, self::THIRD_VIDEO_PATH,self::THIRD_VIDEO_ROLE);
    }

    /**
     * @param string[] $roles
     */
    private function createVideo(ObjectManager $manager, int $categoryId,string $videoPath, int $roleId): void
    {

        $category = $this->em->getRepository(Category::class)->find($categoryId);
        $role = $this->em->getRepository(Role::class)->find($roleId);

        $videoEntity = new Video();
        $videoEntity->setCategory($category);
        $videoEntity->setVideoFilePath($videoPath);

        $videoRole = new VideoRole();
        $videoRole->setRole($role);
        $videoRole->setVideo($videoEntity);

        $videoEntity->getVideoRoles()->add($videoRole);

        $manager->persist($videoEntity);
        $manager->flush();
    }
}
