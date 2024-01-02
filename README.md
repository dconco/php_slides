# PhpSlides

***PhpSlides*** is php framework mainly designed for already created foundation templates used in creating of `Router`, `Api` and `database management` 💫.

Use ***PhpSlides*** in creating of Very based secured Router, Api & database, created inbuilt template database which accepts - `MySql` & `Sqlite` database 🔥✨ can also setup other database.

It has by default in preventing SQL injections, it prevents project from XXS attacks & CSRF 🔐.

It's a good practice for a beginner in Php to start with ***PhpSlides***

## How PhpSlides works:

- With PhpSlides, all request coming from the server are redirecting to PhpSlides for verifing routing. 
  And checks if request matches the registered routes, else it returns a 404 Not Found Page 📌.

- No request can access any files or folders in a PhpSlides project unless specified in routing 📌.
- By default it returns 404 Not Found page if they navigates to any files or folders or any requests received if it hasn't been registered 📌.

- Want to visit a file directly? It'll be Configured in the Slides config file, but can only access files in the public directory if specified 📌.
- Also you can specify the types of files to access, or specify particular extensions that can be requested from each folders in the public directory or all folders 📌.
- It accepts all types of files, Images, videos, audios, documents or plain texts 📌.

- Read PhpSlides default codes and understand each codes function, codes are neat and readable 💯.


## PhpSlides Examples & Explain

Firstly, we create our Slides Project by executing this command in your Terminal 
if Composer is already installed.

```bash
   composer create-project dconco/php_slides slide_project
```

Where the `slide_project` is the project name you which to create & it's the project directory.
And it's going to create the project with the specified name at the target directory where you install it

If composer is not yet install. Install it by executing:
```bash
    pkg install composer
```
<span style="color:green">And we're all setted.</span>

We would open our project on vscode or PhpStorm or any other Php editors.

Let's start our project on browser, you would setup & start the Apache server in your Xampp or any other Php server. 
If you're using phone, you may use AwebServer for Php server.
And open the host in your browser, you would see a default template.

## Routing

There are different methods of Request Route. 
This Route methods are used receiving request from the client side
- GET Route
- POST Route
- PUT Route
- UPDATE Route
- DELETE Route 
- VIEW Route _(almost the same as GET)_
- REDIRECT Route

### Let's start with the View Routing

Open the `routes/route.php` file in your editor. Open the `routes` folder, the the `route.php` file.
By default you'll see a dashboard route been registered, you may remove it to start from scratch.

Let's create a simple blog slides as example. 
So we would register our routes, we would need a `Login Page`, `Register`, `Profile`, and `Posts Page`.
Let's register pur routes in the `route.php` file and write example:

```php (slides)
    <?php
    
    include_once dirname(__DIR__) . "/vendor/autoload.php";
    
    use PhpSlides\Route;
    use PhpSlides\view;
    
    Route::config();
    
    Route::view("/login", "::Login");
    
    ?>
```

This example above is explained:

Firstly we imported our autoload file from the vendor directory so we can use all our class names.

With `use PhpSlides\Route` we use it in importing our Route class to use.

The `Route::config` function must be specified at the beginning of our codes.
It makes PhpSlides to configure our website Routes and makes it very secured 
which allows to to have access in configuring the routing and the requested paths.

The `Route::config` function takes 1 Boolean parameter which indicates whether to allow Logs request.
By default it's setted to true. So on each request it's writes the request header informations to the `.log` file.

And the `Route::view` function allows you to create a view route.

The parameters passed to it, will be two, the first parameter specifies the route that should be requested to render the second parameter.

The second parameters render the files in the view directory which can be accessible with __::__ (___Double Colon___) and the file name. 
Any files we creates in the view directory must be in the format `fileName.view.php` which the `fileName` is the name of the view file
and the `.view.php` is the file extension, so PhpSlides will notice that it's a view file.

So let's create a new file called `Login.view.php` as we've registered it in the route.
Then we can write a small example HTML code.

```php (slides)
    <html>
        <head>
            <title>Login Page</title>
        </head>
        <body>
            <h2>Login Page</h2>
            
            <input type="email" name="emai" />
            <input type="password" name="password" />
            
            <button type="submit">Login</button>
        </body>
    </html>
```

### GET Route Method 

Example of GET Route method 

```php (slides)
    <?php
    
    use PhpSlides\Route;
    use PhpSlides\view;
    
    Route::config();
    
    Route::get("/login", Route::view("::Login"));
    
    ?>
```

You see the difference between View & GET Route.
In view Route, you don't need to GET the view file with `Route::view`, you writes the view as a string in the 2nd parameter.

But in GET route, the 2nd parameters which is used as a callback function takes any types of code. eg, Strings, Array, Function.
Since it returns it directly to the client side.
With GET routes, request method of the particular url to receive must be a GET request, so as all routes methods.

___GET Route with Closure Function method___

```php (slides)
    Route::get("/login", function() {
        return Route::view("::Login");
    });
```

It returns the Login view page, we can return any php values to the browser.

All routes methods has second parameters as callback function which takes function methods or any other methods.

___GET Route method with url Parameters with Closures___

```php (slides)
    Route::get("/posts/{id}", function(int $id) {
        return "Posts ID = " . $id
    });
```

This above example register a new GET route with /posts request which receive GET request with a function closure parameters of ID.
If the request was /posts/2 then it'll return "Posts ID = 2" it gets the ID value and send it to the client side/browser.

## POST Route Method 

Example of POST Route method 

```php (slides)
    <?php
    
    use PhpSlides\Route;
    use PhpSlides\view;
    
    Route::config();
    
    Route::post("/add_user", ());
    
    ?>
```

It calls the POST method, it indicates that the POST route function can only be executed if the REQUEST_METHOD is a POST request. 
And can be used in like submitting form data.

The second parameters is empty, can return a JSON format, 
because POST method is normally used in sending a POST request that returns data in a JSON formats, 2nd parameters can contain any formats for a callback function 

That's how the rest Route method is.

## PUT Route Method 

Example of PUT Route method 

```php (slides)
    Route::put("/user", ());
```

PUT method is just like a POST request, in adding informations or data to a specific file or database.

## UPDATE Route Method 

Example of UPDATE Route method 

```php (slides)
    Route::update("/user/{id}", ());
```

UPDATE method is normally used in updating information like in database.

### DELETE Route Method 

Example of DELETE Route method 

```php (slides)
    Route::delete("/user/{id}", ());
```

DELETE method is normally used likely in deleting informations in the database.

### ANY Route Method 

Example of ANY Route method

```php (slides)
    Route::any("/user/{id}", ());
```

With `Route::any()` method can be used if you want to accept all types of request.
Can be either POST, GET, PUT, UPDATE or DELETE, depending on the requested route method.

## Create NotFound Errors Route

Example of NotFound Route method 

```php (slides)
    Route::any("*", Route::view("::errors::404"));
```

In this above NotFound example, we created an ANY Route
and make the first parameter to be `*` 
which indicates to return whenever there's no routes matches the requested URL.

The NotFound route should be at the ending of the registered routes, 
so it can be executed when all above routes are mismatched.

And in the second parameter, we navigates to `view` folder
and created a folder named `errors` inside the `view` directory,
then create a page called `404.view.php`

## Multiple Route URL Rendering

You can creates a multiple routes url that'll render a page or a function.

Create multiple URLs with array and list of URLs in it. 
Can use any route methods.

___NOTE:___ You cannot use multiple URLs when using routes parameters with {} curly braces

```php (slides)
    Route::view(["/", "/index", "/dashboard"], "::dashboard");
```

This example explains that whenever the requested URL matches the URLs specified in the array,
and it renders the `dashboard.view.php` in the browser.
All route methods accepts multiple URL. Can also create multiple URL for 404 page.

## Route Controller with Closures

`PhpSlides` allows you to be able to access Route Parameters keys in class controller.

You can use any Route Methods for Routing Controller except View method.
Let's go with GET method.

We do create our simple class component in the `Controller` directory at the root directory of your project.

Navigates to the `Controller` directory and create a controller name of your choice but must be in this format `ClassName` + `Controller.php` which should be `ClassNameController.php`.

So let's create a new Controller called `UserController.php`.
Let's navigates to the created file and write some codes in it.

```php (slides)
    <?php
    
    namespace PhpSlides\Controller;
    
    final class UserController extends Controller
    {
        public function __invoke(int $id) {
            return "<h2>Get User with ID = {$id}</h2>";
        }
    }
    
    ?>
```

In this example above, we created a file called `UserController.php` in the `/Controller` folder.

And we created a namespace for the class controller that'll be used in calling the class.
We created the class called `UserController` and extends it to the `Controller` class, 
which allows you to access some protected functions.

Make sure after every created class components which has a namespace you should run this below command for autoloading of each classes.
```bash
    composer slides-refresh
```

The final keywords in the class describes that the class should be final and cannot be extended to another class,
you may remove it if the class should be extended.

And we create our public function called __invoke which gets the closure parameter in route, 
that'll be used to get the url params and returns value for the callback function. 
So it gets the closure $id parameters, and describes it as an integer using the `int` before the param name.

Let's register the user routes and make it to be GET route, can make it any type of route request depending on the usage.

```php (slides)
    <?php
    
    use PhpSlides\Route;
    use PhpSlides\Controller\UserController;
    
    Route::config();
    
    Route::get("/user/{id}", [ UserController::class ]);
    
    ?>
```

In this above example, we already created a class called `UserController`, 
And we created a GET route method which has a URL parameter of `id`.

Then we render the `UserController` class, which the `id` parameter has been sent to the `UserController` class with the `__invoke` function.

### Route Controller with Class Method

In our class controller, we can also create multiple methods for different Route request.

```php (slides)
    <?php

    final class UserController extends Controller
    {
        public function __invoke() {
            return "<h2>Invoked User Successful. Receieved all users successfully.</h2>";
        }
        public function User($id) {
            return "<h2>Received an ID - $id for a user.</h2>";
        }
    }

    ?> 
```

In the `UserController` we created another method called `User()` which takes one parameter as `$id` for the `id` URL request parameter.

So let's use the `User()` class method in the route.

```php (slides)
    Route::get("/user", [ UserController::class ]);
    Route::get("/user/{id}", [ UserController::class, 'User' ]);
```

In first function doesn't has a URL parameter, because in this case we're returning all available users, not each user.

And we created the second function as `/user/{id}`, that means we passed the `id` URL parameter into the `User()` route method.

To use the `User()` method by passing the method name which is `User` as string into the route controller array, at the 2nd index of the array, can only take two array values.

Can add as many methods as possible, and many URL parameters as you can.

### Multiple URL Parameters with Closures

Example below for creating multiple URL parameters:

```php (slides)
    Route::get("/user/{id}/post/{post_id}", function($user_id, $post_id)
    {
        return "User ID = $user_id & Post ID = $post_id";
    });
```

It has two URL parameters called `id` and `post_id` for user id and post id, then we gets the URL parameters in the closure function parameter. 
Same thing as route controller method.

But make sure the function closure parameters variable cannot be the same else it might turns conflict, but the URL parameters may be the same.

## PhpSlides Configurations

Configurations in PhpSlides, makes you have full access in configuring the way request can be sent/received in routings.
And routing configurations can be setup in the `phpslides.config.json` file.

By default everywhere in the web will be blank even though they navigates to any pages. So the web routing is used to add pages to the web if they follow any links, it'll return the page for the URL. And if setupped `Not Found page`, it'll be used whenever the page does not exist.

By default if they open any urls to any path of the page, the web will be blank unless added the `Not Found page` so it'll return it, because to make your website secured they can only access the part you want them to access.

And the configurations in PhpSlides makes it easy in allowing you to configure the part a user can view, to make a very secured website..As they can only view any files in the public folder, but cannot view the folders there apart from Routing.

___Example of Configuration in PhpSlides__

Open the `phpslides.config.json` at the root directory of your project.

```json
    {
        "charset": "UTF-8"
    }
```

This above example specifies the charsets to be used in returning all files and routing pages to the brower.
But can change it at anytime with PHP code, if you want some part to be changed.

```json
    {
        "public": {
            "/": [ "*" ]
        }
    }
```

We added a `public` key in the json file, which specifies that inside the `public` directory, the files and nested files in folders it can access

We added the `/` key which specifies the root of the `public` directory. So we added it has `*` _(Asterisk)_. Which specifies to access all files in the root of the `public` directory. And we can specify any type of extensions they can access, even though the file exist and they try to access the file that the extension are not available in the cconfiguration, it'll return a `Not Found page`. They can write multiple extesnsions in the array. And can also write `image` whereby they can access all images, `video` or `audio`. 
```json
    "/": [ "image", "audio", "video" ] 
```

On each nested folders inside the `public` directory will be specified as an array of extensions in the config file.
And if you want to allow all nested folders, you would just specify the folder in the json file and the extension it can allow in all nested directories.

Like let's say directories are in this formats:

```text
    public:
        assets:
            image.jpg
            file.pdf
            vendor:
                bootstrap.min.js
        images:
            image.png
            image1.jpg
            file.html
```

So this example is explained:
In the `public` directory we have `assets` and `images` folder. so in the `assets` folder we have 1 image, 1 PDF file and 1 `vendor` folder which contain `bootstrap.min.js`. So if we want to configure it::

```json
    {
        "public": {
            "assets": [
                "jpg",
                "js"
            ],
            "images": [ "image", "video" ]
        }
    }
```

In this example we only created configurations for the `assets` and `images` directory, so all files and folders in the `assets` directory will use the extensions added in the `assets` directory which specifies that in this example, the `js` extension specified will be allowed also in the `vendor` folder, or we add the `vendor` key to the json file.

So as we configure the `assets` directory they can only access the `jpg` file and the `js` file in the `vendor` folder.

For the `images` directory we specified that it can only access the any files that are `image` or `video`, so it allows the 2 files there which are `png` & `jpg` and return `Not Found page` whenever they tries to access the `file.html` which extension is not added.

### View Public Files in Web

To view all files that are in `public` directory with files in nested folders. You woudn't add the `public` folder before getting files, it'll return `Not Found page`. So like the above example, to access the file in the `assets` folder. We would follow the url: `http://localhost:8000/assets/image.jpg` and not `http://localhost:8000/public/assets/image.jpg`. 
And if files are in the root directory of the project, it would be accessed directly after the host url: `http://localhost:8000/image.jpg`.


# Version 1.1.0
In this version, slides provides way in dealing with view page 🔥
Create your view template file in the `views` directory, in this format `fileName` + `.view.php` extension. which is `fileName.view.php`

PhpSlides created some special view syntax.
- @view
- ::view
- ::root
- <include !/>
- `<? ?>`

Lets get this started..

```php (slides)
  <!DOCTYPE html>
  <html>
    <!-- Php codes if needed -->
    <?
    
    ?>
  
    <head>
      <!-- Meta Tags Info -->
      <title>Page Title</title>
    </head>
    <body>
      <!-- Body contents -->
    </body>
  </html>
```

In a view template page where we writes HTML codes, PHP tag should not starts the file, 
instead write the php tag and codes inside the `<html>` tag or any other places apart from the starting, 
should always start HTML documents with the `<html>` tag and not `<?php` tag

The `<? ?>` tag in the view template is short php tag used in writing short php codes. 
like using if statement, or echoing data to the html page. Example codes:

```php (slides)
  <body>
    <? if ($user): ?>
      <h1>Hello <? @view $user ?></h1>
    <? else: ?>
      <h1>Hello Guest</h1>
    <? endif; ?>
    
    <p>
      <? @view 'welcome to our blog spot!' ?>
    </p>
  </body>
```

This example is explained as: in the body element we check if a user returns true then it'll add the `h1` element with the text Hello with the user variable value which is returned using the @view keyword in slides.
Else it'll return and `h1` element with text Hello Guest
In the `<p>` tag we wrote php codes which uses the `@view` keyword to return the string/value to the `<p>` element.

__::view & ::root__

The `::view` is just a word written only in the view template files which return the root location after the server name in the project starting from the `/`. Usually used to link public files.
And the `::root` is returning the root location of your project and not has the `::view`, `::root` is used in php in including any files of the project, 
but `::view` can just access only what the browser/client side can acces, but cannot access any files in project when not in `public` directory.

Now let's imagine we have images we would access in the public directory and extra php file we would need to include in the view page.

```php (slides)
  <body>
    <? include '::root/components/extraFile.php' ?>
    
    <div>
      <img src="::view/assets/icon.png" alt="Icon" />
    </div>
  </body>
```

In this case the root of the project is indicated as `::root` and inside the project we created a folder called `components` and new file called `extraFile.php` for any php codes to include in the view page.

The `::view` is the root location of the server host, so let's imagine the host url as `http://localhost:8000/` so it's indicated as `::view` and normally in slides, 
all files and folders in `public` directory are extended in the root of the url as `::view/` and not `::view/public`

So the url as `::view/assets/icon.png` in the `public` directory, created a `assets` folder and `icon.png` image.

__SLIDES INCLUDE__

Slides provides way for you to include another view template file to a view page, and not only a php files.

__2 ways of including view page__

- Using the `<include !/>` html tag
- Using the `slides_include` php function

With the `<include !/>` html tag, you can include other php/view page to the current view page. 
Which allows you to write slides view template in other files to include. And can also include any other php file located anywhere in the project.

```html
  <body>
    <include path="::root/components/extraFile.php" !/>
    <include path="::root/views/viewPage.view.php" !/>
  </body>
```

So in the first tag we included a php file located at the `components` directory.
And the second tag we included a view template file located in the `views` directory.
And can only use the `<include !/>` tag in a view template page.

__slides_include__

This function provides a way for you to include view files in any php file & not only on the view template page.

```php (slides)
  <? @view slides_include('::root/comp/extraFile.php') ?>
  <? @view slides_include('::root/views/page.view.php') ?>
```


# Version 1.2.0

## API

PhpSlides realeased APIs in -v1.2.0, which allows you to work with API in your project.
API is just like Route but it's rendering data back to the request and not the browser.
Slides provide the `Api` function which allows you to register an API route. And setted up the API controller in the `web.php` file.
Register your new API route in the `routes/api.php` file

```php (slides)
  <?php
    use PhpSlides\Api;
    
    Api::get();
    Api::post();
    Api::put();
    Api::patch();
    Api::update();
    Api::delete();
  ?>
```

In the above example blocks of code, we create APIs and those are the API request method that are available in the API.
The `Api` function only takes 2 parameters, the API route url and the second parameter is the controller class method.

___NOTE___ that before you can create API, make sure you setted up the `Route::config()` function in the `route.php` file even though you're not to use the web Route.

Let's create an API for the given request route `"/api/users"` with a POST method which would show the list of available users in a database.
And create another route `"/api/users/{id}"` also a POST method which would show a particular user by the given id.

```php (slides)
  <?php
    use PhpSlides\Api;
    
    Api::post("/api/users");
    Api::post("/api/users/{id}", @user);
  ?>
```

So in the first Api we didn't provide a second parameter for the method to use which by default is `__invoke` method.
And in the second we use the `@user` which declears the `user` method of the same controller class. The `@` target the method name of the controller class.

Let's first create our controller class in the `Controller/Api` directory. Let's name it `UserController`. 
In our controller names, it is must the `Controller` to end a file, which describes a controller file.

```php (slides)
  <?php
  
  namespace PhpSlides\Controller;
  
  final class UserController extends Controller
  {
    function __invoke() {
      $response = ['data' => 'We have gotten all users id successful'];
      return json_encode($response);
    }
    
    function user(int $id) {
      $response = ['data' => 'The particular user id = $id'];
      return json_encode($response);
    }
  }
  
  ?>
```

In the `UserController` class we created the two functional methods and retun an encoded json format.

Not yet done.. We need to register each API routes url for controller class in the `src/web.php` file.

```php (slides)
  <?php
  
  use PhpSlides\Controller\UserController;
  
  return [
    '/api/users' => UserController::class,
    '/api/users/{id}' => UserController::class
  ];
  
  ?>
```


We returned the register API routes now API is ready to be consumed ❤️💯.


***HURRAY as you enjoy using PhpSlides!!!***

***More functions are coming in the next versions***