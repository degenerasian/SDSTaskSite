<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

    <?php
        //  Check if user is logged in, else redirect to login page
        session_start();
        if(!isset($_SESSION['userid'])){
            header('location: ./login.php');
        }        
        require_once "services/db_config.php";
        require_once "modules/nav.php";
        get_navbar();
        $projectid = $_GET['projectid'];

        // create a query to get project
        $query = "SELECT p.*, u.*
                    FROM projects p
                    INNER JOIN p_members pm on p.projectid = pm.projectid
                    INNER JOIN users u on u.userid = pm.userid
                    WHERE p.projectid = 2";

        $results = mysqli_query($con, $query);
        $requests = array();
    
        while ($row = mysqli_fetch_assoc($results)) {
            $requests[] = $row;
        }
    ?>
    <body class="bg-body-tertiary">
        <?php foreach ($requests as $request) { ?>
            <p><?php echo $request['f_name'] . " " . $request['l_name'] ?></p>
        <?php } ?>
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
