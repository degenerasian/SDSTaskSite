<?php
require_once "db_config.php";

if(isset($_POST['editproject'])) {
    $projectid = mysqli_escape_string($con, $_GET['projectid']);
    $project_name = mysqli_escape_string($con, $_POST['project_name']);
    $project_desc = mysqli_real_escape_string($con, $_POST['project_desc']);

    $query = "UPDATE projects
                SET project_name = ?, project_desc = ?
                WHERE projectid = $projectid";

    try {
        $con->begin_transaction();

        $stmt = $con->prepare($query);
        $stmt->bind_param('ss', $project_name, $project_desc);
        $stmt->execute();

        $con->commit();
    } catch (\Throwable $e){
        echo "Error occured. Rolling back...";
        $con->rollback();
        throw $e;
    }

    header('location: ../project.php?projectid=' . $projectid);
}   