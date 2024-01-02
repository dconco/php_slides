<!-- 
    | For an HTML page to render, php tag should not start the page, it'll render it as an PHP page
    | Instead write the <!doctype html> tag which declears HTML 5 then the <html> tag before writing any PHP codes,
    | then it'll evaluate the PHP codes in the HTML.
    | You can write PHP codes anywhere in the file but never start HTML page with PHP codes.
-->

<!DOCTYPE html>
<html lang="en">

<!-- PHP code start -->
<? '' ?>
<!-- // End PHP code -->

<head>
    <title>Dashboard | PhpSlides</title>
    <include path="::root/views/components/Header.php" ! />

    <style>
        .logo {
            width: 45%;
            animation: ReSeize 1.3s ease-in-out infinite;
        }

        .logo img {
            width: 100%;
        }

        .description,
        .link {
            margin: auto;
            color: wheat;
            font-size: 15px;
            text-align: center;
            font-weight: 400;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        .link {
            text-decoration: underline;
        }

        .link:hover {
            color: whitesmoke;
        }
    </style>
</head>


<!-- View Contents Begins -->

<body>
    <div class="container">
        <div class="logo">
            <img src="::view/assets/images/svg/logo-no-background.svg" alt="PhpSlides Logo">
        </div>

        <div class="description">
            <p>
                PhpSlides let you create a secured Routing in php and secured API, which prevents SQL injections, and from XSS attack & CSRF.
            </p>
            <p>
                <a href="//packagist.org/packages/dconco/php_slides" class="link">
                    <? 'Click to view documentation' ?>
                </a>
            </p>
        </div>

        <a href="./any"><button class="btn">Navigate To Not Found Page</button></a>
    </div>
</body>

</html>