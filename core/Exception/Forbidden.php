<?php
namespace SmyPhp\Core\Exception;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core\Exception;
*/ 
class Forbidden extends \Exception
{
    protected $message = 'You don\'t have permission to access this page';
    protected $code = 403;
}