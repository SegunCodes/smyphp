<?php

use SmyPhp\Core\Application;
use SmyPhp\Core\Database\Database;
require 'core/Helpers.php';
require_once __DIR__."/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = import(__DIR__."/config/database.php");
$app = new Application(__DIR__, $config);

$app->db->saveMigrations();
