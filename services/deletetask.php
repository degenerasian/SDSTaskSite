<?php
require_once "db_config.php";

    $id = mysqli_escape_string($con, $_GET['taskid']);
    $projectid = mysqli_escape_string($con, $_GET['projectid']);
    
    // echo $id;
    // echo "<br>";
    // echo $projectid;

    $query = "DELETE FROM tasks WHERE taskid = ?";
    try {
        $con->begin_transaction();

        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $con->commit();
    } catch (Throwable $e) {
        $con->rollback();
        throw $e;
    }

        header('location: ../project.php?projectid=' . $projectid);
