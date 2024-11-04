<?php
require_once "db_config.php";

if(isset($_POST['adduser'])) {
    $f_name = mysqli_escape_string($con, $_POST['firstname']);
    $l_name = mysqli_escape_string($con, $_POST['lastname']);
    $email = mysqli_escape_string($con, $_POST['email']);
    $password = mysqli_escape_string($con, $_POST['password']);
    $privilege = mysqli_escape_string($con, $_POST['privilege']);

    $query = "INSERT INTO users (f_name, l_name, email, password, privilege)
                VALUES (?, ?, ?, ?, ?)";

    $stmt = $con->prepare($query);
    $stmt->bind_param('sssss', $f_name, $l_name, $email, $password, $privilege);
    $stmt->execute();

    header('location: ../users.php');

}   