<?php
// src/Event/UserRegisteredEvent.php
namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class UserRegisteredEvent extends Event
{
    // Define the event name as a constant (this is useful for identifying the event)
    public const NAME = 'user.registered';

    // Add any data you want to pass with the event (e.g., a user object)
    protected $user;

    // Constructor to initialize the event data

    public function __construct($user)
    {
        $this->user = $user;
    }

    // Getter method for accessing the user data
    public function getUser()
    {
        return $this->user;
    }
}
