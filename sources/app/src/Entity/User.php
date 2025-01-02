<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Groups({"user:read"})

     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserRole", mappedBy="user")
     */
    private $userRoles;

    /**
     * @ORM\Column(name="login", type="string", unique=true)
     *
     * @var string
     * @Assert\NotBlank()
     * @Groups({"user:read"})

     */
    private $login;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="password", type="string")
     *
     * @var string|null
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinTable(name="user_role")
     * @Groups({"user:read"})
     */
    private $roles;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @var DateTime
     */
    private $created_at;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     *
     * @var DateTime
     */
    private $updated_at;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     *
     * @throws Exception
     */
    public function onPrePersist(): void
    {
        $this->id = Uuid::uuid4();
        $this->created_at = new DateTime('now');
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getUsername(): string
    {
        return $this->login;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $password): void
    {
        $this->plainPassword = $password;

        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        // The bcrypt algorithm doesn't require a separate salt.
        return null;
    }

    /**
     * @return string[]
     * @Groups({"user:read"})
     */
    public function getRoles(): array
    {
        return $this->roles->map(function (Role $role) {
            return $role->getName(); // Assuming Role entity has getName() method
        })->toArray();
    }

    /**
     * @return Collection|Role[]
     * @Groups({"user:read"})
     */
    public function getCollectionRoles()
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
