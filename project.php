<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

    <!--  PHP code to get project details, associated users, etc.  -->
    <?php
        //  Check if user is logged in, else redirect to login page
        session_start();
        if(!isset($_SESSION['userid'])){
            header('location: ./login.php');
        }        
        require_once "services/db_config.php";
        require_once "modules/nav.php";
        get_navbar();
        $projectid = mysqli_escape_string($con, $_GET['projectid']);
        $query = "SELECT projects.project_name
                    FROM projects
                    WHERE projectid = ?";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $projectid);
        $stmt->execute();
        $result = $stmt->get_result();
        $project = mysqli_fetch_assoc($result);

        // echo $project['project_name'];
        
        // create a query to get project and user details
        $query = "SELECT p.*, u.f_name, u.l_name, u.email
                    FROM projects p
                    INNER JOIN p_members pm on p.projectid = pm.projectid
                    INNER JOIN users u on u.userid = pm.userid
                    WHERE p.projectid = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $projectid);
        $stmt->execute();
        $results = $stmt->get_result();
        $requests = array();
    
        while ($row = mysqli_fetch_assoc($results)) {
            $requests[] = $row;
        }
    ?>

    <body class="bg-body-tertiary">
        <div class="container-fluid px-5 py-3">
            <h1><?php echo $project['project_name'] ?></h1>
        <div class="row row-cols-auto">
        <?php foreach ($requests as $request) { ?>
            <p><?php  echo '<pre>'; print_r($request); echo '</pre>'; ?></p>
        <?php } ?>
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
