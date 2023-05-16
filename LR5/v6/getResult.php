<?php
$json_str = file_get_contents('php://input'); 
$arr = json_decode($json_str, true);

// сумма матрицы
function array_multisum($arr, $mode = 0)
{
    $sum = 0;
    foreach($arr as $v1 => $row)
    {
        foreach($row as $v2 => $cell)
        {
            if(($mode == 0 
            || ($mode == 1 && $v1 != $v2))
            && is_numeric($cell)
            )
                $sum += $cell;
        }
    }
    return $sum; 
}

// дейкстры 
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
        $curNode = NULL;
        
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
        if($curNode === NULL 
            || $curNode == $toNode 
            || $dist[$curNode] == INF
        )
        {
            break;
        }
        // это удаление из очереди
        $queue = array_values(array_diff($queue, [$curNode]));

        // отмечаем текущую вершину пройденной и удаляем из "очереди"

        // ЗДЕСЬ ИДЕТ ПЕРЕСЧЕТ МЕТОК - ПУТЕЙ ДО ВЕРШИН ///

        // если у данной вершины есть смежные вершины
        if (isset($matrix[$curNode])) {
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
            'path' => '< отсутствует >',
            'len' => 0,
            'status' => 0,
            'start' => $frNode,
            'finish' => $toNode
        ];
    }

    return $result;
} 

// вычисляем матрицу крат путей
function getMatrixD($graph)
{
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

    return $response['matrix'];
}


$n = count($arr[0]); // кол-во вершин
$m = count($arr); // кол-во дуг

$matrixA = array_fill(0, $n, array_fill(0, $n, 0));

foreach($arr as $e => $row)
{
    $start = array_search(1, $row);
    $finish = array_search(-1, $row);
    
    if($start !== false && $finish !== false)
    {
        $matrixA[$start][$finish] = 1;
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Невалидные данные, проверьте ввод'
        ]);
        return;
    }
}

// получаем матрицу путей по матрицы смежности
$matrixD = getMatrixD($matrixA);
// print_r($matrixD);

// получаем абсолютную компактность
$q = array_multisum($matrixD, 1);
// print_r($q);

// расчет Zi
$z = [];
for($i = 0; $i < $n; ++$i)
{
    $sum = array_sum($matrixD[$i]);
    $z[] = round(($q / 2) * ($sum ** (-1)), 4);
}
// print_r($z);

$zmax = max($z);
// var_dump($zmax);

$b = ( ($n - 1) * (2 * $zmax - $n) ) / ($zmax * ($n - 2));
// var_dump($b);

echo json_encode([
    'matrixA' => $matrixA,
    'matrixD' => $matrixD,
    'q' => $q,
    'zi' => $z,
    'zmax' => $zmax,
    'b' => $b,
]);

/* $sumA = array_multisum($matrixA);
$r = ( 1 / (2 * ($n - 1)) ) * $sumA - 1;

$gsred = (2 * $m) / $n;
$g = []; // степени вершин
$m = array_multisum($matrixNeor) / 2; // количество ребер
foreach($matrixNeor as $v => $row)
{
    $g[$v] = array_sum($row);
}

$eps = NULL;
if($r >= 0)
{
    // расчет eps
    $eps = 0;
    foreach($g as $cell)
    {
        $eps += ($cell - $gsred) ** 2;
    }
}

$messR = '';
switch(true)
{
    case $r < 0:
        $messR = 'несвязная система';
        break;
    case $r == 0:         
        $messR = 'связная система';
        break;
    default:
        $messR = 'надежная система';
}


echo json_encode([
    'r' => $r,
    'eps' => $eps === NULL ? "-" : round($eps, 3),
    'matrixA' => $matrixA,
    'stepeniVartex' => $g,
    'messR' => $messR
]);
 */