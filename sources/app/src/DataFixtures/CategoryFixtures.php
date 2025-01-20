<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

final class CategoryFixtures extends Fixture
{
    public const DEFAULT_FIRST_CATEGORY = 'Structural Engineering';

    public const DEFAULT_SECOND_CATEGORY = 'Geotechnical Engineering';

    public const DEFAULT_THIRD_CATEGORY = 'Transportation Engineering';

    public function load(ObjectManager $manager): void
    {
        $connection = $manager->getConnection();
        $connection->executeQuery('TRUNCATE TABLE categories');
        $this->createCategory($manager, self::DEFAULT_FIRST_CATEGORY);
        $this->createCategory($manager, self::DEFAULT_SECOND_CATEGORY);
        $this->createCategory($manager, self::DEFAULT_THIRD_CATEGORY);

    }

    /**
     * @param string[] $roles
     */
    private function createCategory(ObjectManager $manager, string $categoryName): void
    {
        $categoryEntity = new Category();
        $categoryEntity->setName($categoryName);
        $manager->persist($categoryEntity);
        $manager->flush();
    }
}
