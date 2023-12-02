<?php
if (isset($_POST['btn']))
{
    $name = $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];

    if (!is_dir('file:///storage/emulated/0/highchat'))
    {
        mkdir('file:///storage/emulated/0/highchat');
    }
    $upload = move_uploaded_file(
        $tmp_name,
        'file:///storage/emulated/0/highchat/' . $name
    );

    if ($upload)
    {
        echo 'success';
    }
    else
    {
        echo 'error';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Upload Files Locally</title>
</head>

<body>
    <h1>UPLOAD FILES</h1>
    <br />

    <form enctype="multipart/form-data" method="POST" action="<?php echo $_SESSION['PHP_SELF'] ?>">
        <input type="file" name="file">
        <br />
        <br />
        <button type="submit" name="btn">Upload</button>
    </form>
</body>

</html>