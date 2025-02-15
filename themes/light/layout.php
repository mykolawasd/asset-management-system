<?php
use \Models\User;
?><!doctype html>

    <html lang="en">
        <head>

            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title><?= $title ?></title>
            
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
            <link rel="stylesheet" href="/themes/light/css/style.css">

    </head>
    <div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-md-3 mb-2 mb-md-0">
            <a class="d-inline-flex link-body-emphasis text-decoration-none align-items-center gap-2" href="/">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-box" viewBox="0 0 16 16">
                <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/>
            </svg>
            <span class="fs-4 fw-semibold">Assets Hub</span>
            </a>
        </div>
        <div class="col-md-6">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <?php if (User::isAdmin()): ?>
                    <a href="/assets/create" class="btn btn-success">
                        <i class="bi bi-file-earmark-plus"></i> Create
                    </a>
                    <a href="/assets/search" class="btn btn-secondary">
                        <i class="bi bi-search"></i> Search
                    </a>
                <?php endif; ?>

            </div>
        </div>
    

        <div class="col-md-3 text-end">
            <?php if (isset($_SESSION['user'])): ?>

            
            <p>Welcome, <?php e($_SESSION['user']['username']); ?></p>


            <a href="/users/logout">Logout</a>
            <?php else: ?>
            <a href="/users/login">Login</a>
            <a href="/users/register">Register</a>
            <?php endif; ?>
        </div>





    </header>
    </div>
    <div class="container">
    <body>
        <?= $content ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="/scripts/tags-autocomplete.js"></script>
    </body>
    </div>


    <div class="container">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <p class="col-md-4 mb-0 text-body-secondary">© 2025 Mykola Shevchenko</p>
    </footer>
    </div>


</html>


