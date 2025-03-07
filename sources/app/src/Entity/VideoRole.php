<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRoleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class VideoRole
{
    use TimestampableTrait;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Video", inversedBy="videoRoles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $video;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="videoRoles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    // Getters and Setters

    public function setVideo(Video $video): void
    {
        $this->video = $video;
    }

    public function setRole(Role $role): void
    {
        $this->role = $role;
    }
}
