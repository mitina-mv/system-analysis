<?php
// $json_str = file_get_contents('php://input'); 
// $arr = json_decode($json_str, true);

// множество левых инц
$arr = [
    [3],
    [1],
    [4],
    [2, 7],
    [4, 7],
    [4],
    [6]
];

echo "<pre>";

// приведение входных данных к нужному виду
foreach($arr as &$mass)
{
    foreach($mass as &$v)
    {
        --$v;
    }
}
// исходная матрица смежности
$lastMatrixA = array_fill(
    0, 
    count($arr), 
    array_fill(0, count($arr), 0)
);

// + создаем массив соседей
$near = [];

foreach($arr as $v2 => $vertex)
{
    foreach($vertex as $v1)
    {
        $lastMatrixA[$v1][$v2] = 1;
        $near[$v1][] = $v2;
    }
}

// print_r($near);
// print_r(getPossibleVertex($near, 4));

$reverseNear = [];

foreach($near as $sv => $arNear)
{
    foreach($arNear as $ev)
    {
        $reverseNear[$ev][] = $sv;
    }
}
// print_r($reverseNear);
$queuePosibleVertex = array_keys($near);
$posibleVertex = getPossibleVertexReq($near, 1, $queuePosibleVertex);

$queueUnposibleVertex = array_keys($reverseNear);
$unposibleVertex = getPossibleVertexReq($reverseNear, 1, $queueUnposibleVertex);

print_r($posibleVertex);
print_r($unposibleVertex);

$graph = array_intersect($posibleVertex, $unposibleVertex);
print_r($graph);

// пробуем рекурсию
function getPossibleVertexReq($near, $vertex, &$queue)
{
    // если у вершины нет соседей, то из нее никуда нельзя прийти
    if(!isset($near[$vertex])) {
        $keyCurVertex = array_search ($vertex, $queue);
        unset($queue[$keyCurVertex]);

        return [$vertex];
    }

    // если мы прошли все вершины
    if(count($queue) == 0)
    {
        echo "<h1>dsfd</h1>";
        return [];
    }

    $set = array_merge([$vertex], $near[$vertex]);

    $keyCurVertex = array_search ($vertex, $queue);
    unset($queue[$keyCurVertex]);

    foreach($near[$vertex] as $nearVertex)
    {
        
        if(in_array($nearVertex, $queue)) {
            $set = array_merge(
                $set, 
                getPossibleVertexReq($near, $nearVertex, $queue)
            );
    
            $keyCurVertex = array_search ($nearVertex, $queue);
            unset($queue[$keyCurVertex]);
        }
    }


    return array_unique($set);
}

function getPossibleVertex($near, $vertex)
{
    // если у вершины нет соседей, то из нее никуда нельзя прийти
    if(!isset($near[$vertex])) {
        return [$vertex];
    }
    
    $set = [];
    $set[] = $vertex;

    $curVertex = $vertex;
    $stop = false;
    $queue = array_keys($near);

    // удаляем из очереди вершины без соседей
    foreach($near as $v)
    {
        if(!isset($v))
        {
            $keyCurVertex = array_search ($v, $queue);
            unset($queue[$keyCurVertex]);
        }
    }

    while(!$stop && count($queue) > 0)
    {
        $stop = true;

        if(isset($near[$curVertex]))
        {
            $stop = false;
            $set = array_merge($set, $near[$curVertex]);
        }

        $keyCurVertex = array_search ($curVertex, $queue);
        unset($queue[$keyCurVertex]);
        $queue = array_values($queue);
        
        $lastNear = $near[$curVertex];

        // обновляем текущую вершину
        // выбираем среди ее соседей ту, которую еще не проходили и у которой есть соседи
        // наличие соседей обязательно, т. к. тупиковые вершины уже были занесены на пред. шаге
        foreach($near[$curVertex] as $nearVertex)
        {
            if(in_array($nearVertex, $queue)
                && isset($near[$nearVertex])
            ) {
                $curVertex = $nearVertex;
                $set = array_merge($set, $near[$curVertex]);
                // break;
            }
        }

        // если текущей вершины нет в очереди, то она уже была удалена
        // значит, через foreach нам не удалось обновить вершину
        // либо у всех оставшихся нет соседей, либо они уже пройдены
        // вершины без соседей не будут удалены из очереди, т.к. в них мы просто не попадем
        if(!in_array($curVertex, $queue)) {
            $curVertex = array_intersect($queue, $lastNear)[0];
        }

        if(!in_array($curVertex, $queue)) {
            $stop = true;

        }
            // $curVertex = $queue[0];
    }

    return array_values(array_unique($set));
}


/* echo json_encode([
    'lastMatrix' => $lastMatrixA,

]); */