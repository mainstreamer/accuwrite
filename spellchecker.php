<?php
require __DIR__ . '/vendor/autoload.php';
$redis = new Predis\Client();

foreach ($_POST['text'] as $array) {
    $value = addslashes(trim($array['value']));
    if (empty($value)) {
        continue;
    }
    
    if (!$redis->exists($value)) {
        //Here we are finding for a similar word
        $response[] = findSimilarWord($array['id'], $value);
    } else {
        $response[] = ['id' => $array['id'], 'value' => "This word is correct!!!"];
    }
}

function findSimilarWord($id, $value) {
    return ['id' => $id, 'value' => "This word is bad!!!"];
}

print json_encode($response);