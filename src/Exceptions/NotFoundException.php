<?php

namespace GeekBrains\LevelTwo\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \Exception implements NotFoundExceptionInterface
{
}
