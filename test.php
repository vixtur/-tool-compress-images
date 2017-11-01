<?php
require 'vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

use Spatie\ImageOptimizer\OptimizerChainFactory;
$optimizerChain = OptimizerChainFactory::create();
$limit = 0;
$_ENV["OPTIMIZE_ONCE"] ?  ($limit = $_ENV["OPTIMIZE_ONCE"]) : ($limit = 100000);
echo $limit;
?>