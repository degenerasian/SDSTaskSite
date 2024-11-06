<?php
require_once "db_config.php";
session_start();

if(isset($_POST['comment'])) {
    
    $comment_text = mysqli_escape_string($con, $_POST['commentbody']);
    $userid = mysqli_escape_string($con, $_SESSION['userid']);
    $taskid = mysqli_escape_string($con, $_GET['taskid']);

    $query = "INSERT INTO comments (comment_text, userid, taskid)
                VALUES (?, ?, ?)";

    $stmt = $con->prepare($query);
    $stmt->bind_param('sii', $comment_text, $userid, $taskid);
    $stmt->execute();
    
    if(isset($_FILES['attachment'])){
        $commentid = mysqli_insert_id($con);
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
    
                    $query = "INSERT INTO img (image, commentid)
                                VALUES (?, ?)";
                        $stmt = $con->prepare($query);
                        $stmt->bind_param('si', $new_img_name, $commentid);
                        $stmt->execute();
    
                        move_uploaded_file($tmp_name, $img_upload_path);
                } else {
                    echo "<script>alert('Invalid file type.');</script>";
                    header('location: ../task.php?taskid=' . $taskid);
                }
    
                // echo $img_ex . "<br>";
            } else {
                echo "<script>alert('Error uploading image.');</script>";
                header('location: ../task.php?taskid=' . $taskid);
            }
    
        }
        header('location: ../task.php?taskid=' . $taskid);
    }
    header('location: ../task.php?taskid=' . $taskid);
}   