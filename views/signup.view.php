<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register</title>
</head>

<body>
    <h1>Register Page</h1>
    <br />

    <form method="POST">
        <div class="name">
            <label for="name">Fullname</label><br />
            <input type="text" name="fullname" id="name" />
        </div>

        <br />
        <div class="email">
            <label for="email">Email</label><br />
            <input type="email" name="email" id="email" />
        </div>

        <br />
        <div class="pwd">
            <label for="pwd">Password</label><br />
            <input type="password" name="password" id="pwd" />
        </div>

        <br />
        <div class="btn">
            <button type="submit">Register</button>
        </div>
    </form>


    <!-- JAVASCRIPT LINKS-->
    <script src="public/src/axios/axios.min.js"></script>
    <script src="public/src/signup.js"></script>
</body>

</html>