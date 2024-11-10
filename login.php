<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Sign In | SDS Task Site</title>
    <link rel="shortcut icon" type="image/png" href="img/favicon.png"><!-- Favicon|image in web browser -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<?php
session_start();
?>
<body style='background-color: #f7f7f7'>
   
<form method="POST" action="services/login.php">
<div class="container shadow position-absolute top-50 start-50 translate-middle" style='width: 22%'>
    <center><img src='img/sdheader.jpg' class='w-100 mt-3'></center>

    <br><h4 style='text-align: center'>Sign In</h4>

    <div data-mdb-input-init class="form-outline mt-4 mx-3">
        <input type="email" id="form3Example3" class="form-control form-control-lg" id="email" name="email" placeholder="Email Address" required/>
    </div>
            
    <div data-mdb-input-init class="form-outline mt-4 mx-3">
        <input type="password" id="form3Example3" class="form-control form-control-lg" id="password" name="password" placeholder="Password"  required/>
    </div>

    <div class="row align-items-center mt-5 mb-5 mx-5">
        <center><button type="submit" name='submit' class="btn btn-outline-secondary w-75" style='height:3em'>Sign In</button></center>
    </div>
</div>
</form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>