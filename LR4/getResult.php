<?php
$json_str = file_get_contents('php://input'); 
$data = json_decode($json_str, true);

$graph = $data['graph'];

// реализация алгоритма прима
function prim(&$graph, $start)
{
    $q = []; // queue
    $p = []; // parent
    $path = array_fill(0, count($graph), 0); // paths

    foreach (array_keys($graph) as $k) {
        $q[$k] = INF;
        $path[$k] = 0;
    }

    $q[$start] = 0;
    $p[$start] = NULL;
    $lastQueue = [];

    asort($q);

    while ($q) {
        // get the minimum value
        $keys = array_keys($q);
        $u = $keys[0];

        foreach ($graph[$u] as $v => $weight) {
            if ($weight > 0 && in_array($v, $keys) && $weight < $q[$v]) {
                $p[$v] = $u;
                $q[$v] = $weight;
                
            }
        }

        $lastQueue = $q;

        unset($q[$u]);

        if($lastQueue == $q)
        {
            break;
        }

        asort($q);
    }

    $curVertex = $start;
    $lastP = $p;
    $newP = [];
    $q = array_keys($graph);

    foreach($q as $v)
    {
        if($v == $start || $lastQueue[$v] == INF)
        {
            unset($q[$v]);
        }
    }

    $newP[$start] = NULL;
    unset($lastP[$start]);

    while(count($q) > 0)
    {
        $keys = array_keys($lastP, $curVertex);

        if(count($keys) > 0)
        {
            foreach($keys as $outV)
            {
                $newP[$outV] = $p[$outV];
            }
            unset($q[$curVertex]);
            $curVertex = $keys[0];
        } else {
            unset($q[$curVertex]);
            $curVertex = current($q);
        }
    }

    foreach($newP as $outV => $innerV)
    {
        if($outV == $start)
        {
            $path[$outV] = NULL;
        }
        else
        {
            $path[$outV] = $path[$innerV] + $graph[$innerV][$outV];
        }
            
    }

    foreach($lastQueue as $key => $v)
    {
        if($key !== $start && $v == INF)
        {
            $path[$key] = "∞";
        }
    }

    return [
        'path' => $path,
        'tree' => $newP
    ];
}

$matrixPath = [];
// перебор вершин для получения кратчайших путей из всех вершин во все вершины
foreach(array_keys($graph) as $v)
{
    $matrixPath[$v] = prim($graph, $v);
    $tmp = [];

    foreach($matrixPath[$v]['tree'] as $key => $item)
    {
        $tmp[] = $item === null ? "исход " . ($key + 1) : ($item + 1) . " -> " . ($key + 1);
    }
    $matrixPath[$v]['tree'] = $tmp;
}

echo json_encode($matrixPath);