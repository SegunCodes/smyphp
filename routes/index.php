<?php

require dirname(__DIR__).'/config/app.php';
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for your application. 
|
*/

$app->router->get('/', function(){
    return "Hello world";
});
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->get('/login/{id}', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->get('/user/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/send', [AuthController::class, 'sendMail']);
$app->router->get('/upload', [AuthController::class, 'testFileUpload']);
$app->router->post('/upload', [AuthController::class, 'testFileUpload']);
$app->router->get('/home', [AppController::class, 'home']);
$app->router->get('/profile', [AuthController::class, 'profile']);
$app->router->get('/contact', [AppController::class, 'contact']);
$app->router->post('/contact', [AppController::class, 'handleContact']);

$app->run();