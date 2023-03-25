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

// функция получения достижимого множества вершин
function getPossibleVertex($near, $vertex, &$queue)
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
                getPossibleVertex($near, $nearVertex, $queue)
            );
    
            $keyCurVertex = array_search ($nearVertex, $queue);
            unset($queue[$keyCurVertex]);
        }
    }

    return $set;
}



// создаем массив соседей
$near = array_fill(0, count($arr), []);
// обратный массив соседей - перевернутый граф
$reverseNear = array_fill(0, count($arr), []);

$edges = [];

foreach($arr as $v2 => $vertex)
{
    foreach($vertex as $v1)
    {
        $near[$v1][] = $v2;
        $reverseNear[$v2][] = $v1;

        $edges["$v1-$v2"] = 0;
    }
}

ksort($edges);
$numEdges = 0;

foreach($edges as &$item)
{
    $item = ++$numEdges;
}

$responseEdges = $edges;

// главная очередь - для вычитания вершин, которые входят в другие подграфы
$mainQueue = array_keys($arr);

// массив связных подграфов
$arGraphs = [];

foreach($arr as $v => $arr)
{
    if(!in_array($v, $mainQueue)) continue;

    if(count($mainQueue) == 0)
        break;
    
    // достижимое множество
    $queuePosibleVertex = array_keys($near);
    $posibleVertex = array_unique(
        getPossibleVertex(
            $near, 
            $v, 
            $queuePosibleVertex
        )
    );
    
    // недостижимое множество
    $queueUnposibleVertex = array_keys($reverseNear);
    $unposibleVertex = array_unique(
        getPossibleVertex(
            $reverseNear, 
            $v, 
            $queueUnposibleVertex
        )
    );
    
    // получаем пересечения множеств - выявление связного подграфа
    $graph = array_intersect(
        $posibleVertex, 
        $unposibleVertex
    );
    
    // получаем связный граф из числа доступные вершин (не вкл. в др. подграфы)
    $resgraph = array_intersect($mainQueue, $graph);
    
    if(empty($resgraph)){
        $keyVertex = array_search ($v, $mainQueue);
        unset($mainQueue[$keyVertex]);

        continue;
    }

    $resedges = [];
    foreach($resgraph as $lk => $lv)
    {
        $keyVertex = array_search ($lv, $mainQueue);
        unset($mainQueue[$keyVertex]);

        if(count($resgraph) > 1 && isset($near[$lv]))
        {
            foreach($near[$lv] as $nv)
            {
                if(in_array($nv, $resgraph))
                {
                    $resedges[] = $edges["$lv-$nv"];
                    unset($edges["$lv-$nv"]);
                }
            }
        }
    }

    $arGraphs[] = [
        'vertex' => array_values($resgraph),
        'edges' => $resedges
    ];
}

$arVertexGraph = array_column($arGraphs, 'vertex');

$matrixA = array_fill(
    0, 
    count($arGraphs), 
    array_fill(0, count($arGraphs), 0)
);

foreach($edges as $key => $num)
{
    $v = explode('-', $key);
    
    $start = null;
    $finish = null;

    foreach($arVertexGraph as $keyArr => $arr)
    {
        if(in_array($v[0], $arr))
            $start = $keyArr;

        if(in_array($v[1], $arr))
            $finish = $keyArr;
    }

    if($start !== null && $finish !== null) {
        $matrixA[$start][$finish] = 1;
    }
}

echo json_encode([
    'matrix' => $matrixA,
    'graphs' => $arGraphs,
    'edges' => $responseEdges
]);