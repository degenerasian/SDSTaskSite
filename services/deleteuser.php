<?php
require_once "db_config.php";

if(isset($_POST['deletedata'])) {
    $id = mysqli_escape_string($con, $_POST['delete_id']);

        $query = "DELETE FROM users WHERE userid = ?";
    try {
        $con->begin_transaction();

        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $con->commit();
    } catch (/Throwable $e) {
        $con->rollback();
        throw $e;
    }

        header('location: ../users.php');

}