<?php

require __DIR__ . '/vendor/autoload.php';


try {
        $redis = new Predis\Client(
            ["scheme" => "tcp",
            "host" => "127.0.0.1",
            "port" => 6379]
        );

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

$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

echo $twig->render('base.html.twig');   