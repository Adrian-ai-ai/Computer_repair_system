<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
echo 'Bootstrap OK';
$app->handleCommand(new Symfony\Component\Console\Input\ArgvInput(['artisan', 'list']));
