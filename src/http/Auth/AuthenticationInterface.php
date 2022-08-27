<?php

namespace GeekBrains\LevelTwo\http\Auth;

use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\Blog\User;

interface AuthenticationInterface
{
    public function user(Request $request): User;
}
