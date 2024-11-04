<?php
require_once "db_config.php";

if(isset($_POST['editdata'])) {
    $id = mysqli_escape_string($con, $_POST['userid']);
    $f_name = mysqli_escape_string($con, $_POST['firstname']);
    $l_name = mysqli_escape_string($con, $_POST['lastname']);
    $email = mysqli_escape_string($con, $_POST['email']);
    $password = mysqli_escape_string($con, $_POST['password']);
    $privilege = mysqli_escape_string($con, $_POST['privilege']);

    $query = "UPDATE users
                SET f_name = ?, l_name = ?, email = ?, password = ?, privilege = ?
                WHERE userid = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param('sssssi', $f_name, $l_name, $email, $password, $privilege, $id);
    $stmt->execute();

    header('location: ../users.php');

}   