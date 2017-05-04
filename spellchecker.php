<?php

require __DIR__ . '/vendor/autoload.php';

$redis = new Predis\Client();
// $input = 'fas afs a asf as f';
//echo json_encode(['fas' => 'fast']);
//echo json_encode(explode(" ",$_POST['text']));

//unset($_POST['text']);

//$correct['one'] = 1;
//$correct['two'] = 2;
//$correct['three'] = 3;
//$correct['four'] = 4;
//$correct['five'] = 5;
//$correct['six'] = 6;
//$correct['seven'] = 7;
//$correct['eight'] = 8;
//$correct['nine'] = 9;
//$correct['ten'] = 10;
$correct['один'] = 1;
$correct['два'] = 2;
$correct['три'] = 3;
$correct['чотири'] = 4;
$correct["п'ять"] = 5;
$correct['шість'] = 6;
$correct['сім'] = 7;
$correct['вісім'] = 8;
$correct["дев'ять"] = 9;
$correct['десять'] = 10;


//return json_encode(var_dump($_POST['text']));
//return json_encode($_POST['text']);

$response[0] = '';
foreach ($_POST['text'] as $array) {
    if (empty($array['value'])) {continue;}
    if (!isset($correct[$array['value']])) {
        $response[] = ['id' => $array['id'], 'value' => $array['value']];
    }
//    echo $array['value'];
}

unset($response[0]);

foreach ($response as $key => $val) {
    $response[$key]['value'] = array_search(rand(1,10),$correct);
}

//$response[1] = ['id' => 1, 'value' => 'First'];
//$response[5] = ['id' => 5, 'value' => 'five'];

//$_POST['text'][1] = ['id' => 1, 'value' => 'First'];
//$_POST['text'][2] = ['id' => 2, 'value' => 'Second'];
//$_POST['text'][5] = ['id' => 5, 'value' => 'five'];

echo json_encode($response);
//echo json_encode($_POST['text']);
