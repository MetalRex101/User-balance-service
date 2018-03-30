<?php

namespace App\MessageHandlers;

use App\Services\User;

class BaseHandler
{
    /**
     * @var User
     */
    protected $userService;

    public function __construct(User $userService)
    {
        $this->userService = $userService;
    }
}
