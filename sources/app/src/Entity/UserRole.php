<?php

// src/Entity/UserRole.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRoleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserRole
{
    use TimestampableTrait;

    // Properties and associations
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userRoles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="userRoles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }
}
