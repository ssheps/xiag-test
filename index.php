<?php

require_once __DIR__.'/vendor/autoload.php';

use App\Application;

// Учитывая, что нам не нужен стейт класса, проще сделать метод статическим
$app = new Application();
$app->run();
