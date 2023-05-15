<?php
$json_str = file_get_contents('php://input'); 
$arr = json_decode($json_str, true);

// echo "<pre>";
// умножение матриц
function multiply(&$mat1, &$mat2, &$res)
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
]);