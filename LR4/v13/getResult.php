<?php
$json_str = file_get_contents('php://input'); 
$data = json_decode($json_str, true);

$graph = $data['graph'];

function lee($graph, $start, $finish)
{
    // создание массива соседей
    foreach($graph as $key => $row)
    {
        $tree[$key] = [];

        foreach($row as $keyRow => $cell)
        {
            if($cell != 0)
                $tree[$key][] = $keyRow;
        }
    }

    if(count($tree[$start]) == 0)
    {
        return [
            'path' => 'путей нет, исходная вершина не имеет соседей',
            'len' => 0,
            'status' => 0,
            'start' => $start,
            'finish' => $finish
        ];
    }

    $d = 1; // распространение волны
    $markedNodes = array_fill(0, count($tree), 0);
    $markedNodes[$start] = $d;
    $stop = false;

    // получение всех марштуров - процесс распространения волны
    while($markedNodes[$finish] == 0 && !$stop)
    {
        $stop = true;

        foreach($markedNodes as $keyNode => $val)
        {
            if($val == $d)
            {
                foreach($tree[$keyNode] as $near)
                {
                    if($markedNodes[$near] == 0)
                    {
                        $stop = false;                    
                        $markedNodes[$near] = $d + 1;
                    }
                }
            }
        }

        ++$d;
    }

    // если мы не дошли до финишной вершины
    if($markedNodes[$finish] == 0) {
        return [
            'path' => 'путей нет, вершина недостижима',
            'len' => 0,
            'status' => 0,
            'start' => $start,
            'finish' => $finish
        ];
    }

    $path = [];
    $pathLen = 0;
    $curNode = $finish;

    $reverseGraph = [];
    $reverseTree = [];
    // создание обратного графа для получения пути
    foreach($graph as $sv => $row)
    {
        foreach($row as $ev => $cell)
        {
            $reverseGraph[$ev][$sv] = $cell;

            if($cell != 0) {
                $reverseTree[$ev][] = $sv;
            }
        }
    }

    // развертывание пути и получение конечной траектории и ее длины 
    while($curNode != $start)
    {
        $path[] = $curNode;
        $minWeightEdge = INF;

        $tmpCurNode = $curNode;

        foreach($reverseTree[$tmpCurNode] as $near)
        {
            if($markedNodes[$near] != 0 
                && $markedNodes[$near] == ($markedNodes[$tmpCurNode] - 1)
                && $reverseGraph[$tmpCurNode][$near] < $minWeightEdge
            ) {
                $curNode = $near;
                $minWeightEdge = $reverseGraph[$tmpCurNode][$near];
            }
        } 

        $pathLen += $minWeightEdge;
    }

    // добавляем стартовую вершину в конец пути
    $path[] = $curNode;

    return [
        'path' => $path,
        'len' => $pathLen,
        'status' => 1,
        'start' => $start,
        'finish' => $finish
    ];
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
            $data = lee($graph, $i, $j);

            $itemResponse[$j] = $data['len'] === 0 ? '∞' : $data['len']; 
            $response['data'][] = $data;
        } else {
            $itemResponse[$i] = '-';
        }    
    }

    $response['matrix'][] = $itemResponse;
}

echo json_encode($response);