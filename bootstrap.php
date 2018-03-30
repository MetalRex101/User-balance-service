<?php

require_once __DIR__ . '/vendor/autoload.php';

(new \Dotenv\Dotenv(__DIR__))->load();

use App\App;
use App\ServiceProvider;
use Pimple\Container;

$container = new Container();
// TODO split to multiple providers
$container->register(new ServiceProvider());

(new App($container))->run();
