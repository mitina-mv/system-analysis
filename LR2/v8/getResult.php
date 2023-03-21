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

// массив ребер
$edges = [];
foreach($arr as $v1 => $vertex)
{
    foreach($vertex as $v2)
    {
        $edges["$v1-$v2"]['v1'] = $v1;
        $edges["$v1-$v2"]['v2'] = $v2;
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

// TODO придумать решение получше
$tmpArrRigthInc = array_fill(
    0,
    count($arr),
    []
);

foreach($edges as $edge)
{
    $tmpArrRigthInc[$edge['v2']][] = $edge['v1'];
}

foreach($tmpArrRigthInc as $key => $arrVertex)
{
    $lv = $key + 1;
    $nv = $tmpVertex[$key] + 1;

    $item = "";

    foreach($arrVertex as $k => $v)
    {
        $lv1 = $v + 1;
        $nv1 = $tmpVertex[$v] + 1;

        $item .= "{$nv1} <{$lv1}>";

        if($k != (count($arrVertex) - 1))  $item .= ', ';
    }

    $arrRigthInc["G({$nv} <{$lv}>)"] = $item;
}

ksort($arrRigthInc);

echo json_encode([
    'newSet' => $arrRigthInc,
    'levels' => $levels
]);