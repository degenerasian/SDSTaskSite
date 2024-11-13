<?php
require_once "db_config.php";

session_start();
if(!isset($_SESSION['userid'])){
    header('location: ./login.php');
}

//  for edits made using assignee sidebar only
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

if(isset($_POST['edittask'])) {
    $taskid = mysqli_escape_string($con, $_GET['taskid']);
    $task_name = mysqli_escape_string($con, $_POST['task_name']);
    $task_desc = mysqli_escape_string($con, $_POST['task_desc']);
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
                    SET task_name = ?, task_desc = ?, label = ?, start_date = ?, due_date = ?, time_est = ?
                    WHERE taskid = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('ssssssi', $task_name, $task_desc, $label, $start_date, $due_date, $time_est, $taskid);
        $stmt->execute();

        $con->commit();
    } catch (\Throwable $e) {
        echo "Error occured. Rolling back...";
        $con->rollback();
        throw $e;
    }

    /*
        Image Insert
    */
    if(isset($_FILES['attachment'])){
        echo '<pre>';
        print_r($_FILES['attachment']);
    
        $uploads = $_FILES['attachment'];
        $uploads_num = count($uploads['name']);
    
        for ($i=0; $i < $uploads_num; $i++) { 
            $image_name = $uploads['name'][$i];
            $tmp_name = $uploads['tmp_name'][$i];
            $error = $uploads['error'][$i];
    
            if ($error === 0){
                $img_ex = pathinfo($image_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
    
                $allow_ext = array('jpg', 'jpeg', 'png');
    
                if(in_array($img_ex_lc, $allow_ext)){
                    $new_img_name = uniqid('IMG-', true) . '.' . $img_ex_lc;
                    $img_upload_path = '../uploads/' . $new_img_name;
    
                    $query = "INSERT INTO img (image, taskid)
                                VALUES (?, ?)";
                        $stmt = $con->prepare($query);
                        $stmt->bind_param('si', $new_img_name, $taskid);
                        $stmt->execute();
    
                        move_uploaded_file($tmp_name, $img_upload_path);
                } else {
                    echo "<script>alert('Invalid file type.');</script>";
                    // header('location: ../task.php?taskid=' . $taskid);
                }
    
                // echo $img_ex . "<br>";
            } else {
                // echo "<script>alert('Error uploading image.');</script>";
                // header('location: ../task.php?taskid=' . $taskid);
            }
    
        }
        // header('location: ../task.php?taskid=' . $taskid);
    }

    header('location: ../task.php?taskid=' . $taskid);

}   