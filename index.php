<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

    <?php
        //  Check if user is logged in, else redirect to login page
        session_start();
        if(!isset($_SESSION['userid'])){
            header('location: ./login.php');
        }        
        require_once "services/db_config.php";
        require_once "modules/nav.php";
        get_navbar();
    ?>
    <body class="bg-body-tertiary">
        <!--main-->
        <div class="container-fluid px-5 py-3">
        <h1>SDS Projects</h1>
        <div class="row row-cols-auto">
            <div class="col py-2 pe-3">
            <a class="link-underline link-underline-opacity-0" href="#">
            <div class="card shadow mb-5" style="width: 20rem; height: 20rem;">
                <div class="card-body">
                    <h4>bXTRA Site</h4>
                    <p class="card-text">Nisi id qui esse in ea ut enim ut dolore sint consectetur magna.</p>
                </div>
            </div>
        </a>
            </div>
            <div class="col py-2 pe-3">
            <a class="link-underline link-underline-opacity-0" href="#">
            <div class="card shadow mb-5" style="width: 20rem; height: 20rem;">
                <div class="card-body">
                    <h4>bXTRA Site</h4>
                    <p class="card-text">Nisi id qui esse in ea ut enim ut dolore sint consectetur magna.</p>
                </div>
            </div>
        </a>
            </div>
            <div class="col py-2 pe-3">
            <a class="link-underline link-underline-opacity-0" href="#">
            <div class="card shadow mb-5" style="width: 20rem; height: 20rem;">
                <div class="card-body">
                    <h4>bXTRA Site</h4>
                    <p class="card-text">Nisi id qui esse in ea ut enim ut dolore sint consectetur magna.</p>
                </div>
            </div>
        </a>
            </div>
        </div>
        </div>
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
