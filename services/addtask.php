<?php
require_once "db_config.php";
session_start();
    if(isset($_POST['addtask'])){
        /*
            Task Insert
        */
        $projectid = mysqli_escape_string($con, $_GET['projectid']);
        $task_name = mysqli_escape_string($con, $_POST['task_name']);
        $task_desc = mysqli_escape_string($con, $_POST['task_desc']);
        $label = mysqli_escape_string($con, $_POST['label']);
        $start_date = mysqli_escape_string($con, $_POST['start_date']);
        $due_date = mysqli_escape_string($con, $_POST['due_date']);
        $created_by = mysqli_escape_string($con, $_SESSION['userid']);
        $temp_hours = mysqli_escape_string($con, $_POST['hours']);
        $temp_minutes = mysqli_escape_string($con, $_POST['minutes']);
        echo $start_date;

        // hours is converted to minutes, time_est is in minutes
        $hours = intval($temp_hours) * 60;
        $minutes = intval($temp_minutes);

        if ($start_date == 0000-00-00) {
            $start_date = date("Y-m-d");
        }
        if ($due_date == 0000-00-00) {
            $due_date = date("Y-m-d");
        }

        $time_est = $hours + $minutes;
        
        $query = "INSERT INTO tasks (task_name, task_desc, label, time_est, start_date, due_date, created_by, projectid)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        try {
            $con->begin_transaction();

            $stmt = $con->prepare($query);
            $stmt->bind_param('sssissii', $task_name, $task_desc, $label, $time_est, $start_date, $due_date, $created_by, $projectid);
            $stmt->execute();

            $taskid = mysqli_insert_id($con);
            echo $taskid;

            $con->commit();
            echo "Success";

        } catch (Throwable $e) {
            $con->rollback();
            echo "An error occured. Rolling back...";
            throw $e;
        }


        /*
            Assignee Insert
        */
        if(!empty($_POST['addid'])){
            $add_data = $_POST['addid'];
            echo '<pre>'; print_r($add_data); echo '</pre>';
            echo '<br>';
            //  Add members based on checkbox values
            $query = "INSERT INTO assignee (userid, taskid)
                    VALUES (?, ?)";
            foreach($add_data as $u){
                $stmt = $con->prepare($query);
                $stmt->bind_param('ii', $u, $taskid);
                $stmt->execute();
            }
        } else echo 'No users added <br>';
        
        /*
            Image Insert
        */
        if(isset($_FILES['attachment'])){
            // echo '<pre>';
            // print_r($_FILES['attachment']);
        
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
                    echo "<script>alert('Error uploading image.');</script>";
                    header('location: ../task.php?taskid=' . $taskid);
                }
        
            }
            header('location: ../task.php?taskid=' . $taskid);
            }
        }
    header('location: ../task.php?taskid=' . $taskid);
