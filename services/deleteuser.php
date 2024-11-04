<?php
require_once "db_config.php";

if(isset($_POST['deletedata'])) {
    $id = mysqli_escape_string($con, $_POST['delete_id']);

    $query = "DELETE FROM users WHERE userid = ?";

    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    header('location: ../users.php');

}