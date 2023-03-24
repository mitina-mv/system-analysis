<?php
// $json_str = file_get_contents('php://input'); 
// $data = json_decode($json_str, true);

// $graph = $data['graph'];

$graph = [
    [0, 10, 10, 0],
    [0, 0, 10, 0],
    [10, 0, 0, 10],
    [0, 10, 0, 0],
];

function getShortPath($graph, $frNode, $toNode)
{
    /* $nodes = [];
    $matrix = [];

    foreach($graph as $edge)
    {
        array_push($nodes, $edge[0], $edge[1]); // добавляю концы ребер в массив вершин
        // составляем матрицу смежности (правильное название?)
        $matrix[$edge[0]][] = array("end" => $edge[1], "cost" => $edge[2]);
        $matrix[$edge[1]][] = array("end" => $edge[0], "cost" => $edge[2]);
    }

    // p($matrix);
    $nodes = array_values(array_unique($nodes)); // удаляем дубли вершин

    // перебираем вершины, чтобы установить пути и предыдущие вершины до них
    foreach ($nodes as $node) {
        $dist[$node] = INF; // по умолчанию путь равен бесконечности 
        $previous[$node] = NULL; // предыдущих вершин нет
    }

    // до начальной вершины путь ноль
    $dist[$frNode] = 0;

    // создаю очередь, чтобы удалять из нее вершины по мере прохождения
    $queue = $nodes;

    while(count($queue) > 0)
    {
        $min = INF; // минимум тоже бесконечный
        $curNode = null;
        
        // переираем непройденные вершины
        foreach ($queue as $node)
        {
            // если путь к этой вершине меньше минимума, то
            if ($dist[$node] < $min) 
            { 
                $min = $dist[$node]; // минимум обновляем
                $curNode = $node; // вершину запоминаем
            }
        }

        // если расстояние до этой вершины бесконечно или она целевая, то выходим из while
        // бесконечное расстояние говорит о том, что в вершину нет пути
        if($dist[$curNode] == INF || $curNode == $toNode || $curNode == null)
        {
            if($dist[$curNode] == INF)
                return [
                    'path' => 'путей нет',
                    'len' => 0,
                    'status' => 0,
                    'start' => $frNode,
                    'finish' => $toNode
                ];
        }

        $queue = array_values(array_diff($queue, [$curNode]));

        // отмечаем текущую вершину пройденной и удаляем из "очереди"

        // /* ЗДЕСЬ ИДЕТ ПЕРЕСЧЕТ МЕТОК - ПУТЕЙ ДО ВЕРШИН ///

        // если у данной вершины есть смежные вершины
        if ($matrix[$curNode]) {
            // перебираем смежные вершины
            foreach ($matrix[$curNode] as $nodeEdge) 
            {
                $newDist = $dist[$curNode] + $nodeEdge["cost"]; // считаем путь от найденной вершины к этой

                // если новый путь до смежной вершины короче того, что был высчитан ранее
                if ($newDist < $dist[$nodeEdge["end"]]) 
                {
                    $dist[$nodeEdge["end"]] = $newDist; // обновляем метку пути к конечной вершине ребра
                    $previous[$nodeEdge["end"]] = $curNode; // записываем текущую вершину в предшественники
                }
            }
        }
    } */

    $result = [
        'path' => [],
        // 'len' => $dist[$toNode],
        'len' => 0,
        'status' => 0,
        'start' => $frNode,
        'finish' => $toNode
    ];

    /* // обратным путем идем от конечной вершины к начальной
    if($dist[$toNode] != INF)
    {
        $curNode = $toNode;
        // пока есть предществующие вершины
        while (isset($previous[$curNode])) 
        {
            array_unshift($result['path'], $curNode); // добавляем текущий узел в путь (в начало массива)
            $curNode = $previous[$curNode]; // новым текущим узлом становится предществующий
        }
    
        // добавляем в начало пути вершину-источник
        array_unshift($result['path'], $curNode);
    } */

    return $result;
} 

$response = [
    'data' => [],
    'matrix' => []
];

for($i = 0; $i < count($graph); ++$i)
{
    $itemResponse = [];

    for($j = 0; $j < count($graph); ++$j)
    {
        if($i != $j) {
            $data = getShortPath($graph, $i, $j);

            $itemResponse[$j] = $data['len'] === 0 ? '∞' : $data['len']; 
            $response['data'][] = $data;
        } else {
            $itemResponse[$i] = '-';
        }    
    }

    $response['matrix'][] = $itemResponse;
}

echo json_encode($response);