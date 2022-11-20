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
    4. [Forms](#forms)
    - [Form Builder](#form-builder)
    - [Input Types](#input-types)
    - [Custom Form Labels](#custom-form-labels)
    5. [SQL queries](#sql-queries)
    -  [Query Builders](#query-builders)
    - [Writing Custom SQL Queries](#writing-custom-sql-queries)
    6. [Middlewares](#middlewares)
    7. [Sending Mails](#sending-mail)
    8. [Flash Messages](#flash-messages)
- [Contributing and Vulnerabilities](#contributing-and-vulnerabilities)
- [License](#license)

# DESCRIPTION

Smyphp is a lightweight PHP framework built for developers who need a simple framework to create web applications

# REQUIREMENTS

- php 7.3^
- composer

# INSTALLATION

Clone the repository

```shell
$ 
```
# USAGE

### Starting Application

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


### Database Migration

All migration files should be saved in the `migrations` folder. `The user_migrations.php` is a default migration file and can be used as a boiler plate for creating other migration files.

Tp migrate the migration files, CD into your projects directory and use this command to perform a database migration

```shell
$ php migrate.php
```

### Routes
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
The `{{content}}` is used to display the content of `example.php` qith the layouts from `main.php` file.

### Defining Custom Layout for views

If you do not wish to use the `main.php` file to render files, then do the following:
- create a new file in the layouts folder
- define this new layout file in the controller function that is handling its rendering

`ExampleController.php` file

```php
namespace App\Http\Controllers;
use SmyPhp\Core\Controller\Controller;

class ExampleController extends Controller{
    $this->setLayout('yourLayoutName');
    public function examplePage(){
        return $this->render('yourFileName');
    }
}
```

### Forms

### Form Builder

### Input Types

### Custom Form Labels

### SQL Queries

### Query Builders

### Writing Custom SQL queries

### Middlewares

### Sending Mail

### Flash Messages




# CONTRIBUTING AND VULNERABILITIES
If you would like to contribute or you discover a security vulnerability in the SmyPhp FrameworK, your pull requests are welcome. However, for major changes or ideas on how to improve the library, please create an issue.

# LICENSE

The SmyPhp framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).