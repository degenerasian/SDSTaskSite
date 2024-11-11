<?php
require_once "db_config.php";

    $id = mysqli_escape_string($con, $_GET['commentid']);
    $taskid = mysqli_escape_string($con, $_GET['taskid']);

        $query = "DELETE FROM comments WHERE commentid = ?";
    try {
        $con->begin_transaction();

        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $con->commit();
    } catch (\Throwable $e) {
        $con->rollback();
        throw $e;
    }

        header('location: ../task.php?taskid=' . $taskid);
