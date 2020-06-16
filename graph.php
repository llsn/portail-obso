<?php


require_once __DIR__ . '/vendor/autoload.php';

use Grafizzi\Graph\Graph as Graph;

$graph = new Graph('G');
// create some cities
$rome=$graph->add
$rome = $graph->createVertex('Rome');
$madrid = $graph->createVertex('Madrid');
$cologne = $graph->createVertex('Cologne');

// build some roads
$cologne->createEdgeTo($madrid);
$madrid->createEdgeTo($rome);
// create loop
$rome->createEdgeTo($rome);

foreach ($rome->getVerticesEdgeFrom() as $vertex) {
    echo $vertex->getId().' leads to rome'.PHP_EOL;
    // result: Madrid and Rome itself
}
?>