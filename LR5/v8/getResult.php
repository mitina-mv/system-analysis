<?php
$json_str = file_get_contents('php://input'); 
$arr = json_decode($json_str, true);

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

$n = count($arr[0]); // кол-во вершин
$m = count($arr) / 2; // кол-во дуг

$matrixA = array_fill(0, $n, array_fill(0, $n, 0));
$matrixNeor = $matrixA;

foreach($arr as $e => $row)
{
    $start = array_search(1, $row);
    $finish = array_search(-1, $row);
    
    if($start !== false && $finish !== false)
    {
        $matrixA[$start][$finish] = 1;

        $matrixNeor[$start][$finish] = 1;
        $matrixNeor[$finish][$start] = 1;
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Невалидные данные, проверьте ввод'
        ]);
        return;
    }
}

$sumA = array_multisum($matrixA);
$r = ( 1 / (2 * ($n - 1)) ) * $sumA - 1;

$gsred = (2 * $m) / $n;
$g = []; // степени вершин
$grmp = []; // степени вершин
$m = array_multisum($matrixNeor) / 2; // количество ребер
foreach($matrixNeor as $v => $row)
{
    $g[$v] = array_sum($row);

    if($g[$v] != INF)
        $grmp[$v] = $g[$v];
}

$eps = NULL;
// if($r >= 0)
// {
    // расчет eps
    $eps = 0;
    foreach($grmp as $cell)
    {
        $eps += ($cell - $gsred) ** 2;
    }
// }

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
    'stepeniVartex' => $g === INF ? '∞' : $g,
    'messR' => $messR
]);
