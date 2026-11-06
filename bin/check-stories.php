<?php

declare(strict_types=1);

// Valida todas as aventuras em /stories (destinos quebrados, becos sem saída, nós órfãos).

require dirname(__DIR__) . '/src/bootstrap.php';

use Doppel\Story\StoryLibrary;

$failed = false;
foreach (StoryLibrary::all() as $id => $story) {
    $errors = StoryLibrary::validate($story);
    echo ($errors ? 'ERRO ' : 'OK   ') . "$id (" . count($story['nodes']) . " nós)\n";
    foreach ($errors as $error) {
        echo "     - $error\n";
        $failed = true;
    }
}
exit($failed ? 1 : 0);
