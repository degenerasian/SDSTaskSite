<?php
require_once "db_config.php";

session_start();
if(!isset($_SESSION['userid'])){
    header('location: ./login.php');
}

$projectid = mysqli_escape_string($con, $_GET['projectid']);
// echo $projectid;

if(isset($_POST['manageusers'])) {
    if(!empty($_POST['addid'])){
        $add_data = $_POST['addid'];
        // echo $users_add;
        // echo '<br>';

        // Add members based on checkbox values
        $query = "INSERT INTO p_members (userid, projectid)
                VALUES (?, ?)";
        foreach($add_data as $u){
            $stmt = $con->prepare($query);
            $stmt->bind_param('ii', $u, $projectid);
            $stmt->execute();
        }
    } else echo 'No users added <br>';

    if(!empty($_POST['removeid'])){
        $remove_data = $_POST['removeid'];
        $users_remove = implode(',', $remove_data);
        // echo $users_remove;

        // Delete members based on checkbox values
        $query = "DELETE FROM p_members 
                    WHERE projectid = ?
                    AND userid IN ($users_remove)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $projectid);
        $stmt->execute();
    } else echo 'No users removed';

    header('location: ../project.php?projectid=' . $projectid);
} else echo 'ERROR';