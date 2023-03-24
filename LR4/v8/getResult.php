<?php
// $json_str = file_get_contents('php://input'); 
// $data = json_decode($json_str, true);

// $graph = $data['graph'];
echo '<pre>';
$graph = [
    [0, 10, 10, 0],
    [0, 0, 0, 0],
    [10, 0, 0, 10],
    [0, 10, 0, 0],
];

function getShortPath($graph, $frNode, $toNode)
{
    $nodes = []; // вершинки
    $matrix = []; // матрица ребер
    $dist = []; // массив дистанций
    $previous = []; // пройденные вершины

    foreach($graph as $sv => $row)
    {
        $nodes[] = $sv;

        $dist[$sv] = INF;  // по умолчанию путь равен бесконечности 
        $previous[$sv] = NULL; // предыдущих вершин нет

        foreach($row as $ev => $cell)
        {
            if($cell != 0)
                $matrix[$sv][] = [
                    "end" => $ev,
                    "cost" => $cell
                ];
        }
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
        if($dist[$curNode] == INF || $curNode == $toNode || $curNode === null)
        {
            break;
        }

        $queue = array_values(array_diff($queue, [$curNode]));

        // отмечаем текущую вершину пройденной и удаляем из "очереди"

        // ЗДЕСЬ ИДЕТ ПЕРЕСЧЕТ МЕТОК - ПУТЕЙ ДО ВЕРШИН ///

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
    }

    $result = [
        'path' => [],
        'len' => $dist[$toNode],
        'status' => 1,
        'start' => $frNode,
        'finish' => $toNode
    ];

    // обратным путем идем от конечной вершины к начальной
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
    } else {
        return [
            'path' => 'путей нет',
            'len' => 0,
            'status' => 0,
            'start' => $frNode,
            'finish' => $toNode
        ];
    }

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