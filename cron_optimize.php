<?php

require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

use Spatie\ImageOptimizer\OptimizerChainFactory;
$optimizerChain = OptimizerChainFactory::create();

$mysqli = new mysqli($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASS"], $_ENV["DB_BASE"]);

$limit = $_ENV["IMAGES_PER_TURN"] ? "LIMIT " . $_ENV["IMAGES_PER_TURN"] : '';

$sql = "SELECT * FROM compression WHERE size_temp<>size_compress {$limit}";

$q =  $mysqli->query($sql);

while($res = $q->fetch_assoc()) {

    $img = $res["url"];

    $optimizerChain
      ->setTimeout(10)
      ->optimize($_ENV["IMAGES_LOCATION"].$img);

    $size_compress = filesize($_ENV["IMAGES_LOCATION"].$img);

    $sql = 'UPDATE ' .
      '`compression` ' .
      'SET ' .
      '`size_compress`=' . $size_compress . ', ' .
      '`size_temp`=' . $size_compress .  ', ' .
      '`size_origin`=' . $res["size_temp"].
      ' WHERE url=\'' . addslashes($img) . '\'';

    $mysqli->query($sql);

    echo $res["url"] . " before: " .$res["size_temp"]." after: ".$size_compress."\n";
}
