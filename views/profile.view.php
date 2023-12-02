<?php
if (isset($req['user_id']))
{
    $user_id = $req['user_id'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profile Page</title>
</head>

<body>
    <h1>
        <?php if ($user_id === $_SESSION['user_id']): ?>
            <h3>You're logged-in as <span class='get_user_name'></span>
            <?php endif; ?>
    </h1>


    <script src="../views/src/axios/axios.min.js"></script>
    <script>
        axios.get('/api/v1/profile/<?php echo $user_id ?>', {
            headers: {
                'Authorization': `Bearer ${getCookie('access_token')}`
            },
            baseURL: 'http://localhost/projects/php_router'
        })
            .then((res) => { })
            .catch((err) => console.log(err));


        /* GET COOKIES */
        function getCookie(cookieName) {
            const cookies = document.cookie.split('; ')

            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].split('=')
                const name = cookie[0]
                const value = decodeURIComponent(cookie[1])

                if (name === cookieName) {
                    return value
                }
            }
            return false
        }
    </script>
</body>

</html>