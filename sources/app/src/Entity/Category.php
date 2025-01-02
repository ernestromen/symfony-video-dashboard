<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="categories")
 */
class Category
{
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="category")
     * @Groups({"category:read"})
     */
    private $videos;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"category:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"category:read"})
     */
    private $name;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
