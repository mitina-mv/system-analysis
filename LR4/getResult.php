<?php
$json_str = file_get_contents('php://input'); 
$data = json_decode($json_str, true);

$graph = $data['graph'];
$start = $data['startVertex'] - 1;

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

        unset($q[$u]);
        asort($q);
    }

    $curVertex = $start;
    $lastP = $p;
    $newP = [];
    $newP[$start] = NULL;
    $q = array_keys($graph);
    unset($q[$start]);
    unset($lastP[$start]);

    // print_r($p);
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
    print_r($newP);

    foreach($newP as $outV => $innerV)
    {
        if($outV == $start)
            $path[$outV] = NULL;
        else
        {
            $path[$outV] = $path[$innerV] + $graph[$innerV][$outV];
        }
            
    }

    return $path;
}

$matrixPath = [];
// $path = prim($graph, 1);
// перебор вершин для получения кратчайших путей из всех вершин во все вершины
foreach(array_keys($graph) as $v)
{
    // $path = prim($graph, $v);
    $matrixPath[$v] = prim($graph, $v);
}




// foreach()
print_r($matrixPath);