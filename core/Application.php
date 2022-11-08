<?php

namespace SmyPhp\Core;
use SmyPhp\Core\Http\Response;
use SmyPhp\Core\Http\Router;
use SmyPhp\Core\Http\Request;
use SmyPhp\Core\Session;
use SmyPhp\Core\Controller\Controller;
use SmyPhp\Core\Database\Database;
use SmyPhp\Core\DatabaseModel;
/**
 * SmyPhp - A simple PHP framework
 * @author SegunCodes
 *  @package SmyPhp\Core
*/ 
class Application{
    public static string $ROOT_DIR;

    public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?DatabaseModel $user;

    public static Application $app;
    public ?Controller $controller = null; 

    public function __construct($root, array $config){
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $root;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('user');
        if($primaryValue){
            $primaryKey = (new $this->userClass)->uniqueKey();
            $this->user = (new $this->userClass)->findOne([$primaryKey => $primaryValue]);
        }else{
            $this->user = null;
        }
    }

    public function isGuest(){
        return !self::$app->user;
    }
    
    public function run(){
        try{
            echo $this->router->resolve();
        }catch(\Exception $e){
            $this->response->setStatus($e->getCode());  
            echo $this->router->getAppViews("error", [
                'exception' => $e
            ]);
        }
    }

    /**
     * @return SmyPhp\Core\Controller\Controller
    */
    public function getController(){
        return $this->controller;
    }

    /**
     * @param SmyPhp\Core\Controller\Controller $controller
     * 
    */
    public function setController(Controller $controller){
        $this->controller = $controller;
    }

    public function login(DatabaseModel $user){
        $this->user = $user;
        $primaryKey = $user->uniqueKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout(){
        $this->user = null;
        $this->session->remove('user');
    }
}