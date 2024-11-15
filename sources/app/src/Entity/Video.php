<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 * @ORM\HasLifecycleCallbacks()
 */

class Video
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $category_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $video_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video_file_path;

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

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getVideoName(): ?string
    {
        return $this->video_name;
    }

    public function setVideoName(string $video_name): self
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
 * Gets triggered only on insert

 * @ORM\PrePersist
 */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
        ;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
