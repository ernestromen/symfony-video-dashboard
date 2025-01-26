<?php

// src/Entity/Role.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Traits\TimestampableTrait;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="roles")
 */
class Role
{
    use TimestampableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"role:read"})
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserRole", mappedBy="role")
     */
    private $userRoles;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"role:read"})

     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="roles")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->videoRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

}
