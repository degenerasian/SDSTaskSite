<?php
require_once "db_config.php";

if(isset($_POST['addproject'])) {
    $project_name = mysqli_escape_string($con, $_POST['project_name']);
    $project_desc = mysqli_escape_string($con, $_POST['project_desc']);

    $query = "INSERT INTO projects (project_name, project_desc)
                VALUES (?, ?)";

    try {
        $con->begin_transaction();

        $stmt = $con->prepare($query);
        $stmt->bind_param('ss', $project_name, $project_desc);
        $stmt->execute();

        $projectid = mysqli_insert_id($con);
        $con->commit();
    } catch (\Throwable $e){
        echo "Error occured. Rolling back...";
        $con->rollback();
        throw $e;
    }

    /*
    Assignee Insert
    */
    if(!empty($_POST['addid'])){
        $add_data = $_POST['addid'];

        // Add members based on checkbox values
        $query = "INSERT INTO p_members (userid, projectid)
                VALUES (?, ?)";
        foreach($add_data as $u){
            try {
                $con->begin_transaction();
            
                $stmt = $con->prepare($query);
                $stmt->bind_param('ii', $u, $projectid);
                $stmt->execute();
                
                $con->commit();
            } catch (\Throwable $e){
                echo "Error occured. Rolling back...";
                $con->rollback();
                throw $e;
            }
        }
    } else echo 'No users added <br>';

    header('location: ../project.php?projectid=' . $projectid);
}   