<?php
    session_start();
    //  Init POST variables from home.php login form
	require_once 'db_config.php';

    /*  TODO Form Validation
    $emailErr = $passwordErr = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST['email'])) {
            $emailErr = "Email is required";
            echo $emailErr;
        } else $email = mysqli_real_escape_string($con, $_POST['email']);
        if (empty($_POST['password'])) {
            $passwordErr = "Password is required";  
        } else $password = mysqli_real_escape_string($con, $_POST['password']);
    } 
    */

    $password = mysqli_real_escape_string($con, $_POST['password']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $query = "SELECT `email`, `password`, `userid`, `privilege` from users WHERE email='$email'";
    $results = mysqli_query($con, $query);
    $exists = mysqli_num_rows($results);

    $table_email = "";
    $table_password = "";
	$table_privilege = "";

    if ($exists) {
        while ($row = mysqli_fetch_assoc($results)) {
            $table_email = $row['email'];
            $table_password = $row['password'];
            $table_userid = $row['userid'];
			$table_privilege = $row['privilege'];

			if (($email == $table_email)) {
				if ($password == $table_password) {
					$_SESSION['userid'] = $table_userid;
					$_SESSION['privilege'] = $table_privilege;
					header('location: ../index.php');
				} else {
					print '<script>alert("Incorrect Password!");</script>';
					print '<script>window.location.assign("../index.php");</script>';
				}
			}
        }
    } else {
        print '<script>alert("Incorrect email!");</script>';
        print '<script>window.location.assign("../index.php");</script>';
    }
?>