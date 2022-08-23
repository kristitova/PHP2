<?php

namespace GeekBrains\LevelTwo\http\Auth;

use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\Blog\User;

interface IdentificationInterface
{
    public function User(Request $request): User;
}
