<?php
require_once "db_config.php";

if(isset($_POST['removedata'])) {
    $taskid = mysqli_escape_string($con, $_GET['taskid']);
    $userid = mysqli_escape_string($con, $_POST['remove_id']);

    $query = "DELETE FROM assignee 
                WHERE userid = ?
                AND taskid = ?";

    try{    
        $con->begin_transaction();
            
        $stmt = $con->prepare($query);
        $stmt->bind_param('ii', $userid, $taskid);
        $stmt->execute();

        $con->commit();
    } catch (\Throwable $e) {
        $con->rollback();
        throw $e;
    }

    header('location: ../task.php?taskid=' . $taskid);

} else echo "ERROR";