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

// расчет eps
$eps = 0;
foreach($g as $cell)
{
    $eps += ($cell - $gsred) ** 2;
}

echo json_encode([
    'r' => $r,
    'eps' => $eps,
    'matrixA' => $matrixA,
    'stepeniVartex' => $g
]);
// print_r($eps);
// print_r($g);
// print_r($gsred);

// echo "<pre>";
// умножение матриц
/* function multiply(&$mat1, &$mat2, &$res)
{
    $N = count($mat1);
    for ($i = 0; $i < $N; $i++)
    {
        for ($j = 0; $j < $N; $j++)
        {
            $res[$i][$j] = 0;
            for ($k = 0; $k < $N; $k++)
                $res[$i][$j] += $mat1[$i][$k] * 
                                $mat2[$k][$j];
        }
    }
}



function getAsum($arMartix, $count)
{
    $resA = array_fill(0, $count, array_fill(0, $count, 0));

    foreach($arMartix as $matrix)
    {
        foreach($matrix as $v1 => $row)
        {
            foreach($row as $v2 => $cell)
            {
                $resA[$v1][$v2] += $cell;
            }
        }
    }

    return $resA;
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

$arStepenMatrix = [];
$arStepenMatrix[1] = $matrixA;
$k = 2;
$curSum = array_multisum($matrixA);

while($curSum !== 0)
{
    // возводим в степень k )
    $tmp = [];
    multiply($arStepenMatrix[$k - 1], $matrixA, $arStepenMatrix[$k]);
    $curSum = array_multisum($arStepenMatrix[$k]);
    ++$k;

    if($k > 7)
    {
        break;
    }
}

if($k > 2)
    unset($arStepenMatrix[$k - 1]);
   
$resA = getAsum($arStepenMatrix, count($arr));
$resC = array_fill(0, count($arr), array_fill(0, count($arr), 0));

foreach($resA as $v1 => $row)
{
    foreach($row as $v2 => $cell)
    {
        if($cell > 0)
        {
            $resC[$v1][$v2] = 1;
        }
    }
}

echo json_encode([
    'matrixA' => $matrixA,
    'stepenA' => $arStepenMatrix,
    'resA' => $resA,
    'resC' => $resC
]); */