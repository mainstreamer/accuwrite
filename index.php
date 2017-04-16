<?php

//namespace Projector; 


require __DIR__ . '/vendor/autoload.php';




try {
    $redis = new Predis\Client(["scheme" => "tcp",
            "host" => "127.0.0.1",
            "port" => 6379]);
//    $redis = new PredisClient();

    // This connection is for a remote server
    /*
        $redis = new PredisClient(array(
            "scheme" => "tcp",
            "host" => "153.202.124.2",
            "port" => 6379
        ));
    */
}
catch (Exception $e) {
    die($e->getMessage());
}

// sets message to contian "Hello world"
//$redis->set('message', 'Hello world');

// gets the value of message
$value = $redis->get('message');

// Hello world
print($value);

echo ($redis->exists('message')) ? "Oui" : "please populate the message key";

//dump($redis)
?>
