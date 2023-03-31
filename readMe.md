# SMYPHP

- [Description](#description)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
    1. [Starting Application](#starting-application)
    2. [Database Migration](#database-migration)
    3. [Routes](#routes)
    -  [Rendering Pages](#rendering-pages)
    -  [Rendering Pages With Parameters](#rendering-pages-with-parameters)
    -  [Views and Layouts](#views-and-layouts)
    -  [Defining Custom Layout for views](#defining-custom-layout-for-views)
    -  [Passing params into routes](#passing-params-into-routes)
    4. [Forms](#forms)
    - [Form Builder](#form-builder)
    - [Input Types](#input-types)
    - [Custom Form Labels](#custom-form-labels)
    - [Handling Form Data](#handling-form-data)
    5. [SQL queries](#sql-queries)
    -  [Query Builders](#query-builders)
    - [Writing Custom SQL Queries](#writing-custom-sql-queries)
    6. [Middlewares](#middlewares)
    7. [Sending Mails](#sending-mail)
    8. [Flash Messages](#flash-messages)
    9. [Image conversion](#image-conversion)
    10. [Sending Json responses in API](#Sending-Json-responses-in-API)
    11. [Getting Authenticated users in API](#Getting-Authenticated-users-in-API)
- [Contributing and Vulnerabilities](#contributing-and-vulnerabilities)
- [License](#license)

# DESCRIPTION

Smyphp is a lightweight PHP framework built for developers who need a simple framework to create web applications

# REQUIREMENTS

- php 7.3^
- composer

# INSTALLATION

```shell
$ composer create-project seguncodes/smyphp yourProjectName
```
# USAGE

### STARTING APPLICATION

CD into your projects directory and run your application using the command below

```shell
$ php smyphp --start
```
Now you open [http://localhost:8000](http://localhost:8000) in your browser to see your application.

OR open with your preferred port

```shell
$ php smyphp --start --port 3344
```
Now you open [http://localhost:3344](http://localhost:3344) in your browser to see your application.

Run the following command for help

```shell
$ php smyphp --help
```


### DATABASE MIGRATION

All migration files should be saved in the `migrations` folder. `The user_migrations.php` is a default migration file and can be used as a boiler plate for creating other migration files.

To migrate the migration files, `cd` into your projects directory and use this command to perform a database migration

```shell
$ php migrate.php
```

### ROUTES
The routes folder contains the assets folder where css, javascript, image and other files can be stored. The routes folder also contains the `index.php` file which is used to handle all routing.

### Rendering Pages

Rendering can be done directly in the `index.php` file , an example is this

```php
$app->router->get('/hello', function(){
    return "Hello world";
});
```
Visit [http://localhost:8000/hello](http://localhost:8000/hello). You're done.

OR rendering could be done using the MVC method , an example is this

`index.php` file

```php
use App\Http\Controllers\ExampleController;

$app->router->get('/hello', [ExampleController::class, 'examplePage']);
```

then in the `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;

class ExampleController extends Controller{

    public function examplePage(){
        return $this->render('yourFileName');
    }
}
```

finally in the views folder, in the `yourFileName.php` file

```php

<h2>Hello World</h2>
```

Visit [http://localhost:8000/hello](http://localhost:8000/hello). You're done.

### Rendering Pages With Parameters

Pages can be rendered with parameters using the MVC method...


`index.php` file

```php
use App\Http\Controllers\ExampleController;

$app->router->get('/hello', [ExampleController::class, 'examplePage']);
```

then in the `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;

class ExampleController extends Controller{

    public function examplePage(){
        return $this->render('yourFileName', [
            'text' => 'hello world'
        ]);
    }
}
```

finally in the views folder, in the `yourFileName.php` file

```php

<h2> <?php echo $text ?> </h2>

```

Visit [http://localhost:8000/hello](http://localhost:8000/hello). You're done.

### Views and Layouts

The Views folder contains the layouts folder, and also contains files that will be displayed on the browser. The layouts folder contains layouts files. NOTE: `main.php` file is the default file.
Here is an example of defining a layout for a view file:

`example.php` file

```php
<div>
    <h2>Hello World</h2>
</div>
```
In layouts folder
`main.php` file

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    {{content}}
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
```
The `{{content}}` is used to display the content of `example.php` with the layouts from `main.php` file.

### Defining Custom Layout for views

If you do not wish to use the `main.php` file to render files, then do the following:
- create a new file in the layouts folder
- define this new layout file in the controller function that is handling its rendering

`ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;

class ExampleController extends Controller{
    public function examplePage(){
        $this->setLayout('yourLayoutName');
        return $this->render('yourFileName');
    }
}
```

The `$this->setLayout()` function is used to set the layout for a particular page, and should be called before the rendering of the page you are setting a layout for.

### Passing params into routes
Params can be passed into routes and queried in controllers, here is an example:

`index.php` file

```php
use App\Http\Controllers\ExampleController;

$app->router->get('/hello/{id}', [ExampleController::class, 'examplePage']);
```

then in the `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use SmyPhp\Core\Http\Request;

class ExampleController extends Controller{

    public function examplePage(Request $request){
        echo '<pre>';
        var_dump($request->getParams());
        echo '</pre>';
        return $this->render('yourFileName');
    }
}
```

`$request->getParams()` is used to get the parameters passed in the url

### FORMS
Forms can be used in the framework using the default HTML forms or using the Framework's form builder method

### Form Builder
Using the Form builder method, in any of your view files , for example a login form... 
in `login.php` in views directory
```php
<?php $form = \SmyPhp\Core\Form\Form::start('', 'post')?>
    <?php echo $form->input($model, 'email') ?>
    <?php echo $form->input($model, 'password')->Password() ?>
    <br>
    <div class="input-group">
        <input type="submit" class="btn btn-block btn-primary" value="Submit">
    </div>
<?php \SmyPhp\Core\Form\Form::stop()?>
```

The `Form::start()` method is used to start the form and takes two arguments `(action, method)`.
The `$form->input()` method is used to call an input field in the form, it takes in two arguments `(model, inputName)`. The `model` parameter is used to reference the Model handling the request for that form; while the `inputName` is the `name` for that input field. 

### Handling Form Data
Form data is handled using controllers. Here is an example:

in `register.php` in views directory
```php
<?php $form = \SmyPhp\Core\Form\Form::start('/register', 'post')?>
    <?php echo $form->input($model, 'email') ?>
    <?php echo $form->input($model, 'password')->Password() ?>
    <br>
    <div class="input-group">
        <input type="submit" class="btn btn-block btn-primary" value="Submit">
    </div>
<?php \SmyPhp\Core\Form\Form::stop()?>
```
Then in `index.php` the route is defined
```php
use App\Http\Controllers\ExampleController;

$app->router->post('/register', [ExampleController::class, 'register']);
```
finally in the `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use SmyPhp\Core\Http\Request;
use App\Models\User;

class ExampleController extends Controller{

    public function register(Request $request){
        $this->setLayout('auth');
        $user = new User();
        //$user references the User model
        if($request->isPost()){
            //your registration logic comes here
            return $this->render('register', [
                'model' =>$user //this is the model being sent to the form in the register page
            ]);
        }
        return $this->render('register', [
            'model' =>$user //this is the model being sent to the form in the register page
        ]);
    }
}
```


### Input Types
The form builder also comes with various input types
```php
<?php $form = \SmyPhp\Core\Form\Form::start('', 'post')?>
    <?php echo $form->input($model, 'password')->Password() ?>
    <?php echo $form->input($model, 'number')->TypeNumber() ?>
    <?php echo $form->input($model, 'checkBox')->CheckBox() ?>
    <?php echo $form->input($model, 'date')->TypeDate() ?>
    <?php echo $form->input($model, 'file')->TypeFile() ?>
    <?php echo $form->input($model, 'radio')->TypeRadio() ?>
    <br>
    <div class="input-group">
        <input type="submit" class="btn btn-block btn-primary" value="Submit">
    </div>
<?php \SmyPhp\Core\Form\Form::stop()?>

//for text area field
echo new TextareaField($model, 'textarea')
```

### Custom Form Labels
The default labels of input fields in the form builder method are the inputNames of the field. The labels can be changed in the model referenced in the `input()` method.

in `login.php` in views directory
```php
<?php $form = \SmyPhp\Core\Form\Form::start('/login', 'post')?>
    <?php echo $form->input($model, 'email') ?>
    <?php echo $form->input($model, 'password')->Password() ?>
    <br>
    <div class="input-group">
        <input type="submit" class="btn btn-block btn-primary" value="Submit">
    </div>
<?php \SmyPhp\Core\Form\Form::stop()?>
```

in the model being referenced in the controller handling the form data, there is a `labels()` method, where the labels can be customized

`Model.php`
```php
<?php

namespace App\Models;

use SmyPhp\Core\DatabaseModel;
class User extends DatabaseModel
{
    //...

    public function labels(): array
    {
        return [
            'email' => 'Your Email',
            'password' => 'Your Password',
        ];
    }
}
```

### SQL QUERIES
Writing SQL queries in the framework can be achieved using the framework's query builders or default SQL statements

### Query Builders
The framework comes with various query builders 
`save()`
The save function saves data into database 
`findOne()` 
finds row WHERE argument exists and returns only 1
```php
use App\Models\User;
$user = (new User)->findOne(['email' => 'youremail@email.com']); //finds row that email exists and returns only 1

/*
|--------------------------------------------------------------------------
| More than one conditions can be passed in
|
*/
$user = (new User)->findOne([
            'email' => 'youremail@email.com',
            'id' => 2
        ]); //finds where row that email AND id exists
```
`findOneOrWhere()`
This takes in two arguments with an OR condition
```php
use App\Models\User;
$user = (new User)->findOneOrWhere([
        'email' => 'youremail@email.com'
    ], ['id' => 2]); //finds where row that email OR id exists
```
`findAll()`
This performs the basic SELECT all functionality in descending order of id
```php
use App\Models\User;
$user = (new User)->findAll(); //finds where row that email OR id exists
```
`findAllWhere()`
This performs the findAll functionality with a WHERE clause
```php
use App\Models\User;
$user = (new User)->findAllWhere([
        'email' => 'youremail@email.com'
    ]); //finds ALL rows that email
```
`findAllOrWhere()`
This performs the findAll functionality with a WHERE clause and an OR condition
```php
use App\Models\User;
$user = (new User)->findAllOrWhere([
        'email' => 'youremail@email.com'
    ], ['id' => 2]); //finds rows where email OR id exists
```
`count()`
This counts the number of columns in a table
```php
use App\Models\User;
$user = (new User)->count(); //returns the number of columns
```
`countWhere()`
This counts the number of columns with a WHERE clause
```php
use App\Models\User;
$user = (new User)->countWhere(['name'=>'john']); //returns the number of columns with name of john
```
`countOrWhere()`
This counts the number of columns with a WHERE clause and an OR condition
```php
use App\Models\User;
$user = (new User)->countOrWhere([
            'name'=>'john'
        ], [
            'status' => 1
        ]); //returns the number of columns with name of john or a status of 1
```
`delete()`
This takes a WHERE clause and deletes a row or rows
```php
use App\Models\User;
$user = (new User)->delete([
            'name'=>'john'
        ]); //deletes the row(s) with name of john
```
`deleteOrWhere()`
This takes a WHERE clause and deletes a row or rows
```php
use App\Models\User;
$user = (new User)->deleteOrWhere([
            'name'=>'john'
        ], [
            'email' => 'youremail@email.com'
        ]); //deletes the row(s) with name of john or email of youremail@email.com
```
`update()`
This takes two arguments, the data to be updated and a WHERE clause
```php
use App\Models\User;
$user = (new User)->update([
            'name'=>'john',
            'status'=> 1
        ], [
            'email' => 'youremail@email.com'
        ]); //sets status to 1 and name to john where the email is youremail@email.com
```
`updateOrWhere()`
This takes three arguments, the data to be updated, a WHERE clause and an OR condition
```php
use App\Models\User;
$user = (new User)->updateOrWhere([
            'name'=>'john',
            'status'=> 1
        ], [
            'email' => 'youremail@email.com'
        ], [
            'id' => 4
        ]); //sets status to 1 and name to john where the email is youremail@email.com OR id is 4
```


### Writing Custom SQL queries
Unlike using the query builders, custom SQL statements can be written, here is an example:
```php
use SmyPhp\Core\DatabaseModel;

$stmt = DatabaseModel::prepare("SELECT count(*) FROM users WHERE id = 2");
// $stmt->bindParam(); //this can be called if you are binding
$stmt->execute();
```

### MIDDLEWARES
The framework includes a middleware that verifies if the user of your application is authenticated. If the user is not authenticated, the middleware will redirect the user to your application's login screen. However, if the user is authenticated, the middleware will allow the request to proceed further into the application.

The first middleware `ApiMiddleware` is used to check for authenticated users on your api and it is called in the controller, the method handling the route that should not be accesible by the user is passed in array of `new Authenticate([''])`.

In `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use App\Http\Middleware\ApiMiddleware;

class ExampleController extends Controller{

    public function __construct(){
        $this->authenticatedMiddleware(new ApiMiddleware(['']));
    }
}
```

The second middleware `Authenticate` middleware is called in the controller and it's used when dealing with dynamic webpages on the framework, the method handling the route that should not be accesible by the user is passed in array of `new Authenticate([''])`.

In `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use App\Http\Middleware\Authenticate;

class ExampleController extends Controller{

    public function __construct(){
        $this->authenticatedMiddleware(new Authenticate(['']));
    }
}
```

To prevent a user from accessing a page after login, add the following code to the top of the file rendering that page; or to set restrictions for users who are logged in or not
```php
use SmyPhp\Core\Application;
if (!Application::$app->isGuest()) {
    Application::$app->response->redirect('/');
}
```
The `isGuest()` function is used to check if there is an existing session

### SENDING MAIL
Sending mails in the framework is achieved using PHPMAILER. To send a mail from the controller , the `MailServiceProvider` class is called.

in `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use App\Providers\MailServiceProvider;

class ExampleController extends Controller{

    public function sendMail(){
        $subject = "subject";
        $email = "youremail@email.com";
        $name = "your name";
        $email_template = Application::$ROOT_DIR."/views/email.php"; //if the email will be sent in a template
        $send = (new MailServiceProvider)->Mail($subject, $email, $name, $email_template);
    }
}
```
To send an hand coded mail, the `MailServiceProvider.php` file in the app/Providers directory can be edited

### Flash Messages

Sending flash messages after a successful request can be achieved from the controller by calling the 
`setflash()` method which takes in two arguments `(key, message)`.
in `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use SmyPhp\Core\Application;

class ExampleController extends Controller{

    public function sendFlash(){
        Application::$app->session->setFlash('success', 'Thanks for joining');
        Application::$app->response->redirect('/'); //this redirects to the route where the flash message will appear
        exit;
    }
}
```

### Image Conversion
When dealing with images using the framework, the image file has to be sent in base64 format to the backend API. To convert an image from the base64 format , the `Image` class is called.

in `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use App\Providers\Image;

class ExampleController extends Controller{

    public function sendMail(){
        $base64Image = "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAUDBAQEAwUEBAQFBQUGBwwIBwcHBw8LCwkMEQ8SEh"; 
        $path = Application::$ROOT_DIR."/routes/assets/uploads";
        $filename = "uploads_".uniqid().".jpg";
        $convertImage = Image::convert($base64Image, $path, $filename);
    }
}
```
To use the `Image` class , make sure the `extension=gd` in your php.ini file is enabled.

### Sending Json responses in API
To send json responses in API using the framework is quite simple and very similar to most php frameworks

in `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use SmyPhp\Core\Http\Request;
use SmyPhp\Core\Http\Response;

class ExampleController extends Controller{

    public function sendResponse(Request $request, Response $response){
         return $response->json([
            "success" => false,
            "message" => "All fields are required"
        ], 400);
    }
}
```
The `json` method takes two arguments, the array data to be returned and the status code

### Getting Authenticated user in API

Getting the authenticated user in your API on the framework is quite simple and similar to most php frameworks
in `ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;
use SmyPhp\Core\Http\Request;
use SmyPhp\Core\Http\Response;
use SmyPhp\Core\Auth;

class ExampleController extends Controller{

    public function sendResponse(Request $request, Response $response){
        $user = Auth::User();
         return $response->json([
            "success" => false,
            "user" => $user
        ], 400);
    }
}
```
`Auth::User()` returns the id of the authenticated user.

# Contributing & Vulnerabilities
If you would like to contribute or you discover a security vulnerability in the SmyPhp FrameworK, your pull requests are welcome. However, for major changes or ideas on how to improve the library, please create an issue.

# License

The SmyPhp framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).