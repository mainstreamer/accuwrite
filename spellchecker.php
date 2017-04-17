<?php

// $input = 'fas afs a asf as f';
//echo json_encode(['fas' => 'fast']);
//echo json_encode(explode(" ",$_POST['text']));

//unset($_POST['text']);

$correct['one'] = true;
$correct['two'] = true;
$correct['three'] = true;
$correct['four'] = true;
$correct['five'] = true;
$correct['six'] = true;
$correct['seven'] = true;
$correct['eight'] = true;
$correct['nine'] = true;
$correct['ten'] = true;


//$response[1] = ['id' => 1, 'value' => 'First'];
//$response[5] = ['id' => 5, 'value' => 'five'];

//$_POST['text'][1] = ['id' => 1, 'value' => 'First'];
//$_POST['text'][2] = ['id' => 2, 'value' => 'Second'];
//$_POST['text'][5] = ['id' => 5, 'value' => 'five'];

echo json_encode($_POST['text']);
