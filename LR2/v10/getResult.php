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
$lastMatrixA = array_fill(
    0, 
    count($arr), 
    array_fill(0, count($arr), 0)
);

// массив ребер
$edges = [];
foreach($arr as $v2 => $vertex)
{
    foreach($vertex as $v1)
    {
        $edges["$v1-$v2"]['v1'] = $v1;
        $edges["$v1-$v2"]['v2'] = $v2;

        $lastMatrixA[$v1][$v2] = 1;
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

$newMatrixA = array_fill(
    0, 
    count($arr), 
    array_fill(0, count($arr), 0)
);

foreach($tmpVertex as $lv => $nv)
{
    foreach($lastMatrixA[$lv] as $lastV2 => $val)
    {
        if($val)
        {
            $newV2 = $tmpVertex[$lastV2];
            $newMatrixA[$nv][$newV2] = 1;
        }
    }
}

// TODO json переворачивается и все перестает работать
$t = [];
foreach($tmpVertex as $lv => $nv){
    $t[] = [
        'lv' => (int)$lv,
        'nv' => (int)$nv
    ];
}

echo json_encode([
    'lastMatrix' => $lastMatrixA,
    'newMatrix' => $newMatrixA,
    'levels' => $levels,
    'namesVertex' => $t,
]);