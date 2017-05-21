<?php

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/Entity/ArrayManager.php';
require __DIR__ . '/Entity/Spellchecker.php';
require __DIR__ . '/Entity/TrieManager.php';

//$redis = unserialize(file_get_contents('triedb'));
//$redis = new Predis\Client();

$redis = new ArrayManager();

$spellchecker = new Spellchecker($redis);
$spellchecker->processInput($_POST['text']);