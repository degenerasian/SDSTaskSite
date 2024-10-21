<?php
    session_start();
    //  Init POST variables from home.php login form
	require_once 'db_config.php';

    $username = ($_POST['username']);
    $password = ($_POST['password']);

    $query = "SELECT `username`, `password`, `userid`, `privilege` from accounts WHERE username='$username'";
    $results = mysqli_query($con, $query);
    $exists = mysqli_num_rows($results);

    $table_users = "";
    $table_password = "";
	$table_privilege = "";

    if ($exists) {
        while ($row = mysqli_fetch_assoc($results)) {
            $table_users = $row['username'];
            $table_password = $row['password'];
            $table_userid = $row['userid'];
			$table_privilege = $row['privilege'];

			if (($username == $table_users)) {
				if ($password == $table_password) {
					$_SESSION['userid'] = $table_userid;
					$_SESSION['privilege'] = $table_privilege;
					header('location: ../index.php');
                    print '<script>window.location.assign("../index.php");</script>';
				} else {
					print '<script>alert("Incorrect Password!");</script>';
					print '<script>window.location.assign("../index.php");</script>';
				}
			}
        }
    } else {
        print '<script>alert("Incorrect Username!");</script>';
        print '<script>window.location.assign("../index.php");</script>';
    }
?>