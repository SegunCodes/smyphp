<?php
namespace SmyPhp\Core\Middleware;
use SmyPhp\Core\Application;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core\Middleware;
*/ 
abstract class BaseMiddleware
{
    abstract public function execute();
}