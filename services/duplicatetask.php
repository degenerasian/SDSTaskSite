<?php
require_once "db_config.php";

    $taskid = mysqli_escape_string($con, $_GET['taskid']);

    // Task Duplication
    $query = "INSERT INTO tasks (task_name, task_desc, label, time_est, start_date, due_date, created_by, projectid) 
                SELECT task_name, task_desc, label, time_est, start_date, due_date, created_by, projectid 
                FROM tasks WHERE taskid = ?";

        try {
            $con->begin_transaction();

            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $taskid);
            $stmt->execute();

            $newtask = mysqli_insert_id($con);

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollback();
            throw $e;
        }

    // Assignee Duplication
    $query = "INSERT INTO assignee (userid, taskid) 
                SELECT userid, ?
                FROM assignee WHERE taskid = ?";

        try {
            $con->begin_transaction();

            $stmt = $con->prepare($query);
            $stmt->bind_param('ii', $newtask, $taskid);
            $stmt->execute();

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollback();
            throw $e;
        }

    // Image Duplication
    $query = "INSERT INTO img (image, taskid) 
                SELECT image, ?
                FROM img WHERE taskid = ?";

        try {
            $con->begin_transaction();

            $stmt = $con->prepare($query);
            $stmt->bind_param('ii', $newtask, $taskid);
            $stmt->execute();

            $con->commit();
        } catch (\Throwable $e) {
            $con->rollback();
            throw $e;
        }
header('location: ../task.php?taskid=' . $newtask);