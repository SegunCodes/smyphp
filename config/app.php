<?php
 
 /**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 * 
 *  @package SmyPhp
*/ 
use SmyPhp\Core\Application;
use SmyPhp\Core\Authorization\Server;

require_once dirname(__DIR__)."/vendor/autoload.php";
// $whoops = new \Whoops\Run;
// $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
// $whoops->register();
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
require dirname(__DIR__).'/Core/Helpers.php';
require dirname(__DIR__).'/autoload.php';

$config = import(dirname(__DIR__)."/config/database.php");

Server::convertPayload();
$app = new Application(dirname(__DIR__), $config);