<?php
$json_str = file_get_contents('php://input'); 
$arr = json_decode($json_str, true);

// echo "<pre>";

// сумма матрицы
function array_multisum($arr)
{
    $sum = 0;
    foreach($arr as $row)
    {
        foreach($row as $cell)
        {
            $sum += $cell;
        }
    }
    return $sum; 
}

function svyzGraph($g, $n)
{
    $flag = true;
    $check = ($n - 1) / 2;

    foreach($g as $cell)
    {
        if($cell < $check)
            $flag = false;
    }

    return $flag;
}

// создаем матрицу А - смежности
$matrixA = [];
foreach($arr as $vertex => $arrVertex)
{
    $matrixA[$vertex] = array_fill(0, count($arr), 0);

    foreach($arrVertex as $v)
    {
        $matrixA[$vertex][$v - 1] = 1;
    }
}

$sumA = array_multisum($matrixA);
$n = count($arr); // количество верршин
$r = ( 1 / (2 * ($n - 1)) ) * $sumA - 1;
// print_r($r);

// составление матрицы неор графа
$matrixANeor = array_fill(0, count($arr), array_fill(0, count($arr), 0));
foreach($arr as $vertex => $arrVertex)
{
    foreach($arrVertex as $v)
    {
        $matrixANeor[$vertex][$v - 1] = 1;
        $matrixANeor[$v - 1][$vertex] = 1;
    }
}

$m = array_multisum($matrixANeor) / 2; // количество ребер
$gsred = (2 * $m) / $n;
$g = []; // степени вершин

foreach($matrixANeor as $v => $row)
{
    $g[$v] = array_sum($row);
}

$eps = NULL;
if($r >= 0)
{
    // расчет eps
    $eps = 0;
    foreach($g as $cell)
    {
        $eps += ($cell - $gsred) ** 2;
    }
}

$messR = '';
switch(true)
{
    case $r < 0:
        $messR = 'несвязная система';
        break;
    case $r == 0:         
        $messR = 'связная система';
        break;
    default:
        $messR = 'надежная система';
}


echo json_encode([
    'r' => $r,
    'eps' => $eps === NULL ? "-" : round($eps, 3),
    'matrixA' => $matrixA,
    'stepeniVartex' => $g,
    'messR' => $messR
]);
