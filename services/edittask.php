<?php
require_once "db_config.php";

session_start();
if(!isset($_SESSION['userid'])){
    header('location: ./login.php');
}

if(isset($_POST['detailedit'])) {
    $taskid = mysqli_escape_string($con, $_GET['taskid']);
    $label = mysqli_escape_string($con, $_POST['label']);
    $start_date = mysqli_escape_string($con, $_POST['start_date']);
    $due_date = mysqli_escape_string($con, $_POST['due_date']);
    $temp_hours = mysqli_escape_string($con, $_POST['hours']);
    $temp_minutes = mysqli_escape_string($con, $_POST['minutes']);

    // hours is converted to minutes, time_est is in minutes
    $hours = intval($temp_hours) * 60;
    $minutes = intval($temp_minutes);

    $time_est = $hours + $minutes;

    try{
        $con->begin_transaction();

        $query = "UPDATE tasks
                    SET label = ?, start_date = ?, due_date = ?, time_est = ?
                    WHERE taskid = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('ssssi', $label, $start_date, $due_date, $time_est, $taskid);
        $stmt->execute();

        $con->commit();
    } catch (\Throwable $e) {
        echo "Error occured. Rolling back...";
        $con->rollback();
        throw $e;
    }

    header('location: ../task.php?taskid=' . $taskid);

}   