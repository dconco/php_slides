<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="title" content="PhpSlides | PHP Framework" />

<link rel="apple-touch-icon" href="::view/assets/icons/icon.png" sizes="234x234" />
<link rel="shortcut icon" href="::view/assets/icons/icon.png" type="image/png" />
<link rel="icon" href="::view/assets/icons/icon.png" type="image/png" />

<!-- CSS Links -->
<link rel="stylesheet" type="text/css" href="::view/styles/App.css">

<!-- Internal Styling -->
<style>
    .container {
        width: 70%;
        height: 70%;
        margin: auto;
        padding: 20px;
        display: flex;
        flex-flow: column;
        overflow-y: auto;
        overflow-x: hidden;
        border-radius: 15px;
        background: #6432c9;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0px 0px 8px 10px #a176f8;
        animation: ZoomIn 0.8s ease-in-out forwards;
    }

    .container::-webkit-scrollbar {
        width: 8px;
        border-radius: 5px;
        background: #bb9fe5;
    }

    .container::-webkit-scrollbar-thumb {
        border-radius: 5px;
        background: #783fe9;
    }

    button.btn {
        border: none;
        margin: 0 auto;
        color: wheat;
        cursor: pointer;
        font-weight: bold;
        border-radius: 5px;
        text-transform: uppercase;
        transition: all 0.2s ease-in-out;
        padding: 10px 40px 10px 40px;
        box-shadow: 0 0 8px #a176f8;
        background: linear-gradient(50deg, darkblue, blue);
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    }

    button.btn:hover {
        background: #783fe9;
        animation: ButtonAnim 0.5s ease-in-out forwards;
    }

    button.btn:active {
        background: #a176f8;
    }
</style>