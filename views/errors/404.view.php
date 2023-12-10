<!-- 
    |   For an HTML page to render, php tag should not start the page, it'll render it as an PHP page
    |   Instead write the <!doctype html> tag which declears HTML 5 then the <html> tag before writing any PHP codes,
    |   then it'll evaluate the PHP codes in the HTML.
    |   You can write PHP codes anywhere in the file but never start HTML page with PHP codes.
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    use PhpSlides\Controller\RouteController;

    // includes the Header.php file
    echo RouteController::get_included_file("::root/views/components/Header.php");
    ?>

    <title>404 | Page Not Found</title>
</head>

<body>
    <div class="container">
        <h3 class="text">404 | Page Not Found</h3>
        <a href="::root::view/"><button class="btn">Navigate Back To Dashboard</button></a>
    </div>
</body>

</html>