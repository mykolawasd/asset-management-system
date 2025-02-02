<doctype html>

    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title><?= $title ?></title>

    </head>
    <header>
        <?php if (isset($_SESSION['user'])): ?>
            <p>Welcome, <?php e($_SESSION['user']['username']); ?></p>
            <a href="/Users/logout">Logout</a>
        <?php endif; ?>


        <h1>Header</h1>


    </header>
    <body>
        <?= $content ?>
    </body>


    <footer>
        <h1>Footer</h1>
    </footer>

</html>


