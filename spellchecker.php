<?php

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/Entity/ArrayManager.php';
require __DIR__ . '/Entity/Spellchecker.php';
require __DIR__ . '/Entity/TrieManager.php';

//$frequency = unserialize(file_get_contents('frequency'));

//$engine = new Predis\Client();
//$engine = new ArrayManager();

if (file_exists('triedb')) {
    $engine = unserialize(file_get_contents('triedb'));
} else {
    $engine = new TrieManager();
}



//echo $fr['мама'];

$spellchecker = new Spellchecker($engine);
//$spellchecker->processInput($_POST['text']);

echo $engine->findNeighbours('cat');