<?php

namespace App\Entity;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="videos")
 */

class Video
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"category:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"category:read"})

     */
    private $category_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"category:read"})
     */
    private $video_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"category:read"})
     */
    private $video_file_path;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VideoRole", mappedBy="video", cascade={"persist"})
     */
    private $videoRoles;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="videos")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="videos")
     * @ORM\JoinTable(name="video_role")
     */

    private $roles;

    public function __construct()
    {
        $this->videoRoles = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(?string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getVideoName(): ?string
    {
        return $this->video_name;
    }

    public function setVideoName(?string $video_name): self
    {
        $this->video_name = $video_name;

        return $this;
    }

    public function getVideoFilePath(): ?string
    {
        return $this->video_file_path;
    }

    public function setVideoFilePath(?string $video_file_path): self
    {
        $this->video_file_path = $video_file_path;

        return $this;
    }
    /**
     * @return Collection
     * 
     */
    public function getVideoRoles(): Collection
    {
        return $this->videoRoles;
    }


    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;

        }

        return $this;
    }

}
