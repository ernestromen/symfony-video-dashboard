<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="category")
     */
    private $videos;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category_name;

    public function __construct()
    {
        $this->videos = new \Doctrine\Common\Collections\ArrayCollection();
    }
        /**
     * Returns all videos associated with this category.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    public function setCategoryName(string $category_name): self
    {
        $this->category_name = $category_name;

        return $this;
    }
}
