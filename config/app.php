<?php
 
 /**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 * 
 *  @package SmyPhp
*/ 
use SmyPhp\Core\Application;
require_once dirname(__DIR__)."/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
require dirname(__DIR__).'/core/Helpers.php';

$config = _import(dirname(__DIR__)."/config/database.php");

$app = new Application(dirname(__DIR__), $config);