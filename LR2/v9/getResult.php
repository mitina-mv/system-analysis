<?php
$json_str = file_get_contents('php://input'); 
$arr = json_decode($json_str, true);

// приведение входных данных к нужному виду
foreach($arr as &$mass)
{
    foreach($mass as &$v)
    {
        --$v;
    }
}

$usedVertex = [];
$noUsedVertex = array_keys($arr);
$newArray = [];
$levels = [];
$nameVertex = 0;

// исходная матрица инциденций
$lastMatrixB = [];

// массив ребер
$edges = [];
foreach($arr as $v1 => $vertex)
{
    foreach($vertex as $v2)
    {        
        $edges["$v1-$v2"]['v1'] = $v1;
        $edges["$v1-$v2"]['v2'] = $v2;

        // заполняем в соответсвии с графом
        $v1i = $v1 + 1;
        $v2i = $v2 + 1;

        $lastMatrixB["$v1i-$v2i"] = array_fill(
            0,
            count($arr),
            0
        );

        $lastMatrixB["$v1i-$v2i"][$v1] = 1;
        $lastMatrixB["$v1i-$v2i"][$v2] = -1;
    }
}

while(count($noUsedVertex) > 0)
{
    $levels[] = [];

    foreach($noUsedVertex as $key => $nv)
    {
        $k = 0;

        // считаем полустепень захода в вершину
        foreach($edges as $edge){
            if($edge['v2'] == $nv)
            {
                ++$k;
            }
        } 

        //вычитаем дуги, иходящие из вершин предыдущих уровней и входящие в вершину i
        foreach($usedVertex as $v)
        {
            foreach($edges as $edge){
                if($edge['v1'] == $v && $edge['v2'] == $nv)
                {
                    --$k;
                }
            }
        }

        if ($k == 0)
        {
            $levels[count($levels) - 1][$nv] = $nameVertex;

            unset($noUsedVertex[$key]);
            --$i;
            ++$nameVertex;
        }
    }

    foreach($levels[count($levels) - 1] as $keyV => $v)
    {
        $usedVertex[] = $keyV;
    }
}

// сюда кладем соотвествие новых и старых вершин (убираем иерархию для упрощения)
$tmpVertex = [];
foreach($levels as $lvl)
{
    foreach($lvl as $k => $v)
    {
        $tmpVertex[$k] = (int)$v;
    }
}

// новая матрица инциденций
$newMatrixB = [];

foreach($edges as $edge => $arVertex)
{
    $lastV1i = $arVertex['v1'] + 1;
    $lastV2i = $arVertex['v2'] + 1;
    
    $nv1 = $tmpVertex[$arVertex['v1']];
    $nv2 = $tmpVertex[$arVertex['v2']];

    $newV1i = $nv1 + 1;
    $newV2i = $nv2 + 1;

    $newMatrixB["$newV1i-$newV2i ($lastV1i-$lastV2i)"] = array_fill(0, count($arr), 0);

    $newMatrixB["$newV1i-$newV2i ($lastV1i-$lastV2i)"][$nv1] = 1;
    $newMatrixB["$newV1i-$newV2i ($lastV1i-$lastV2i)"][$nv2] = -1;
}
ksort($newMatrixB);
ksort($lastMatrixB);

// TODO json переворачивается и все перестает работать
$t = [];
foreach($tmpVertex as $lv => $nv){
    $t[] = [
        'lv' => (int)$lv,
        'nv' => (int)$nv
    ];
}

echo json_encode([
    'lastMatrix' => $lastMatrixB,
    'newMatrix' => $newMatrixB,
    'levels' => $levels,
    'namesVertex' => $t,
]);