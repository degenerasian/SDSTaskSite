<?php
require_once "db_config.php";

session_start();
if(!isset($_SESSION['userid'])){
    header('location: ./login.php');
}

// if($_SESSION['privilege'] != 'Admin') header('location: ./index.php');

$taskid = mysqli_escape_string($con, $_GET['taskid']);
// echo $projectid;

if(isset($_POST['manageassignees'])) {
    if(!empty($_POST['addid'])){
        $add_data = $_POST['addid'];
        echo '<pre>'; print_r($add_data); echo '</pre>';
        // echo '<br>';

        // Add members based on checkbox values
        $query = "INSERT INTO assignee (userid, taskid)
                VALUES (?, ?)";
        foreach($add_data as $u){
            $stmt = $con->prepare($query);
            $stmt->bind_param('ii', $u, $taskid);
            $stmt->execute();
        }
    } else echo 'No users added <br>';

    if(!empty($_POST['removeid'])){
        $remove_data = $_POST['removeid'];
        $users_remove = implode(',', $remove_data);
        // echo $users_remove;

        // Delete members based on checkbox values
        $query = "DELETE FROM assignee 
                    WHERE taskid = ?
                    AND userid IN ($users_remove)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $taskid);
        $stmt->execute();
    } else echo 'No users removed';

    header('location: ../task.php?taskid=' . $taskid);
} else echo 'ERROR';