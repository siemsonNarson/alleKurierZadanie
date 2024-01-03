<?php

namespace App\Core\User\Domain\Event;

use App\Common\EventManager\EventInterface;
use App\Core\User\Domain\User;
class UserCreatedEvent implements EventInterface
{
    public function __construct(public User $user)
    {
    }
}
