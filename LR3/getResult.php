<?php
// $json_str = file_get_contents('php://input'); 
// $arr = json_decode($json_str, true);

// множество левых инц
$arr = [
    [2, 4],
    [4, 3, 5],
    [],
    [4, 3],
    [3, 4],
    [2],
    [5],
    [2]
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

print_r($near);
print_r(getPossibleVertex($near, 2));

function getPossibleVertex($near, $vertex)
{
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

        // обновляем текущую вершину
        // выбираем среди ее соседей ту, которую еще не проходили и у которой есть соседи
        // наличие соседей обязательно, т. к. тупиковые вершины уже были занесены на пред. шаге
        foreach($near[$curVertex] as $nearVertex)
        {
            if(in_array($nearVertex, $queue)
                && isset($near[$nearVertex])
            ) {
                $curVertex = $nearVertex;
            }
        }

        // если текущей вершины нет в очереди, то она уже была удалена
        // значит, через foreach нам не удалось обновить вершину
        // либо у всех оставшихся нет соседей, либо они уже пройдены
        // вершины без соседей не будут удалены из очереди, т.к. в них мы просто не попадем
        if(!in_array($curVertex, $queue))
            $stop = true;
    }
    print_r($set);

    return array_unique($set);
}


/* echo json_encode([
    'lastMatrix' => $lastMatrixA,

]); */