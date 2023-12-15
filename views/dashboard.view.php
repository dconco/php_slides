<!-- 
  | For an HTML page to render, php tag should not start the page, it'll render it as an PHP page
  | Instead write the <!doctype html> tag which declears HTML 5 then the <html> tag before writing any PHP codes,
  | then it'll evaluate the PHP codes in the HTML.
  | You can write PHP codes anywhere in the file but never start HTML page with PHP codes.
-->

<!DOCTYPE html>
<html lang="en">

<!-- PHP code start -->
<?php  ?>
<!-- // End PHP code -->

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="PhpSlides | PHP Framework" />
    <title>Dashboard | PhpSlides</title>

    <link rel="apple-touch-icon" href="::view/assets/logo-squared.png" sizes="1000x1000" />
    <link rel="shortcut icon" href="::view/assets/logo-squared.png" type="image/png" />
    <link rel="icon" href="::view/assets/logo-squared.png" type="image/png" />

    <link rel="stylesheet" type="text/css" href="::view/styles/App.css">
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="::view/assets/svg/logo-no-background.svg" alt="PhpSlides Logo">
        </div>

        <div class="description">
            <p>
              PhpSlides let you create a secured Routing in php and secured API, which prevents SQL injections, and from XSS attack & CSRF.
              <br><br>
              <? @view 'Injected Code' ?>
            </p>
        </div>

        <a href="./any"><button class="btn">Navigate To Not Found Page</button></a>
    </div>
</body>

</html>