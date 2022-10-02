<?php
//1 задание
include 'bubbleSort.php';
include 'quickSort.php';
include 'shakerSort.php';
include 'treeSort.php';

function getArray(): array
{
    $array = [];
    for ($i = 0; $i <= 1000; $i++) {
        $array[] = mt_rand(0, 0);
    }
    return $array;
}

$arr = getArray();
$lastIndex = count($arr) - 1;
$start = microtime(true);
quickSort($arr, 0, $lastIndex);
echo "Сортировка быстрая: " . (microtime(true) - $start) . PHP_EOL;


$arr = getArray();
$start = microtime(true);
bubbleSort($arr);
echo "Сортировка пузырком: " . (microtime(true) - $start) . PHP_EOL;

$arr = getArray();
$start = microtime(true);
shakerSort($arr);
echo "Сортировка шейкерная: " . (microtime(true) - $start) . PHP_EOL;

$arr = getArray();
$start = microtime(true);
treeSort($arr);
echo "Сортировка пирамидальная: " . (microtime(true) - $start) . PHP_EOL;

//самая быстрая пирамидальная сортировка

//2 задание 
$array1 = array("num1" => 1, "num2" => 2, "num3" => 5, "num4" => 1);
$array1 = array_unique($array1);
$del_val = 1;
if (($key = array_search($del_val, $array1)) !== false) {

    unset($array1[$key]);
}
foreach ($array1 as $item => $item_count) {
    echo "Ключ=" . $item . ", Значение=" . $item_count . PHP_EOL;
}
