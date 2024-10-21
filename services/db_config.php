<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'password');
    define('DB_DATABASE', 'sdsts_db');

    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE)
            or die ("Error: Unable to connect to database");
    
    mysqli_select_db($con, DB_DATABASE) or die ("Error: Cannot access database");
?>