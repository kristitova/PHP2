<?php

use Common\ClassName;

function loader($className): void
{
    var_dump($className);
    $array_contains = ['\\', '_'];
    $replace = ['/', '/'];

    $file = str_replace($array_contains, $replace, $className) . ".php";


    if (file_exists($file)) {
        include $file;
    }
}

spl_autoload_register('loader');

$unit = new ClassName(1, 'name', 2);
var_dump($unit);
