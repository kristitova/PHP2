<?php

namespace GeekBrains\LevelTwo\tests\Container;

use GeekBrains\LevelTwo\tests\Container\SomeClassWithParameter;
use GeekBrains\LevelTwo\tests\Container\SomeClassWithoutDependencies;


class ClassDependingOnAnother
{
    public function __construct(
        private SomeClassWithoutDependencies $one,
        private SomeClassWithParameter $two
    ) {
    }
}
