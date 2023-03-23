<?php
/* $json_str = file_get_contents('php://input'); 
$data = json_decode($json_str, true);

$graph = $data['graph']; */

echo "<pre>";

$graph = [
    [0, 0, 0, 10, 0],
    [0, 0, 0, 0, 0],
    [10, 10, 0, 0, 10],
    [0, 0, 10, 0, 10],
    [0, 10, 0, 0, 10]
];

const WALL = -1;
const BLANK = 0;
const STARTVERTEX = 1;
// const FINISHVERTEX = -2; (?)
$workArea = [];

// смещения по алгоритму: вправо, вниз, влево, вверх
const offsetX = [1, 0, -1, 0];
const offsetY = [0, 1, 0, -1];

$ax = 0; $ay = 1; // старт
$bx = 2; $by = 4; // конец

// подготовка рабочей области для построения трасс по алгоритму Ли
// вынести перед функцией
foreach($graph as $key => $row)
{
    foreach($row as $keyRow => $cell)
    {
        if($cell == 0) {
            $workArea[$key][$keyRow] = WALL;
        } else {
            $workArea[$key][$keyRow] = BLANK;
        }
    }
}

$start = 0;
$finish = 1;

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
    echo 'из вершины нет путей';
    return false;
}
print_r($tree);

$d = 1; // распространение волны
$markedNodes = array_fill(0, count($tree), 0);
$markedNodes[$start] = $d;
$stop = false;

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
print_r($markedNodes);

if($markedNodes[$finish] == 0) {
    echo 'вершина недостижима';
    return false;
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
print_r($reverseGraph);
print_r($reverseTree);

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

$path[] = $curNode;

print_r($path);
print_r($pathLen);


echo "</pre>";

// $d = 0; // распространение волны
// $workArea = array_fill(
//     0,
//     count($tree),
//     array_fill(0, count($tree), -1)
// );

// foreach($tree as $nodeKey => $near)
// {
//     foreach($near as $vertex)
//     {
//         $workArea[$nodeKey][(int)$vertex] = 0;
//     }
// }
// echo "<pre>";
// print_r($workArea);
// echo "</pre>";


// проверка, что может существовать
// if($workArea[$ax][$ay] == WALL || $workArea[$bx][$by] == WALL) {
//     echo 'стартовая или конечная вершина - стена, поиск невозможен';
//     return;
// }

/* $d = 0; // распространение волны
$workArea[$ax][$ay] = STARTVERTEX; // стартовая вершина помечена как начальная
$stop = true;

$matrixSize = count($graph);

do {
    $stop = true;

    foreach($workArea as $x => $row)
    {
        foreach($row as $y => $cell)
        {
            if($cell == $d){
                for($i = 0; $i < 4; ++$i)
                {
                    $iy = $y + offsetX[$i];
                    $ix = $x + offsetX[$i];

                    // найдена непройденная вершины
                    // распространяем волну
                    if($iy >= 0 && $ix >= 0 
                        && $iy < $matrixSize && $ix < $matrixSize
                        && $workArea[$ix][$iy] == BLANK
                    ) {
                        $stop = false;
                        $workArea[$ix][$iy] = $d + 1;
                    }
                }
            }
        }
    }

    ++$d;
} while (!$stop && $workArea[$by][$bx] == BLANK);

if($workArea[$by][$bx] == BLANK){
    echo 'невозможно найти путь';
    return;
}

echo "<pre>";
print_r($workArea);
echo "</pre>"; */

// $matrixPath = [];
// перебор вершин для получения кратчайших путей из всех вершин во все вершины
/* foreach(array_keys($graph) as $v)
{
    $matrixPath[$v] = prim($graph, $v);
    $tmp = [];

    // текстовое остовное дерево - иначе сбивается порядок следования на js
    foreach($matrixPath[$v]['tree'] as $key => $item)
    {
        $tmp[] = $item === null ? "исход " . ($key + 1) : ($item + 1) . " -> " . ($key + 1);
    }
    $matrixPath[$v]['tree'] = $tmp;
} */

// echo json_encode($matrixPath);