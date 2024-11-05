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
        require_once "services/get_tasks.php";
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
        $query = "SELECT p.*, u.userid, u.f_name, u.l_name, u.email
                    FROM projects p
                    INNER JOIN p_members pm on p.projectid = pm.projectid
                    INNER JOIN users u on u.userid = pm.userid
                    WHERE p.projectid = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $projectid);
        $stmt->execute();
        $results = $stmt->get_result();
        $members = array();
    
        while ($row = mysqli_fetch_assoc($results)) {
            $members[] = $row;
        }

        //  Query to get non-members for invite
        $query = "SELECT u.userid, u.f_name, u.l_name
                    FROM users u
                    EXCEPT
                    SELECT u.userid, u.f_name, u.l_name
                    FROM projects p
                    INNER JOIN p_members pm on p.projectid = pm.projectid
                    INNER JOIN users u on u.userid = pm.userid
                    WHERE p.projectid = ?";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $projectid);
        $stmt->execute();
        $results = $stmt->get_result();
        $n_members = array();

        while ($row = mysqli_fetch_assoc($results)) {
            $n_members[] = $row;
        }

    ?>

    <body class="bg-body-tertiary">
        <div class="container-fluid px-5 py-3">
            <div class="row row-cols-auto">
                <div class="col mx-4">
                    <h1><strong><?= $project['project_name'] ?></strong></h1>
                </div>
                <div class="col mx-4">
                    <a class="btn btn-primary align-middle my-2 me-2">Add New Task</a>
                    <button type="button" class="btn btn-warning align-middle my-2 me-2" data-bs-toggle="modal" data-bs-target="#inviteModal">Manage Members</button>
                </div>
            </div>
        <?php get_tasks($con, $projectid);?>


    <!--    Invite Modal    -->
        <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Members</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Add to <?= $project['project_name'];?></h5>
                    <form method="POST" action="services/manageusers.php?projectid=<?= $projectid?>">
                    <table class="table table-hover table-borderless">
                        <colgroup>
                        <col style="width:1%;">
                        <col>
                        <thead class="table-dark">
                            <tr>
                                <th>Add</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if($n_members){
                            foreach ($n_members as $n) { ?>
                            <tr>
                                <td><input type="checkbox" name="addid[]" value="<?= $n['userid']?>"></td>
                                <td><?= $n['f_name'] . " " . $n['l_name'];?></td>
                            </tr>
                        <?php }
                            } else { ?>No members available. <?php } ?>
                        </tbody>
                    </table>
                    <hr>
                    <h5>Remove from <?= $project['project_name'];?></h5>
                    <table class="table table-hover table-borderless">
                        <colgroup>
                        <col style="width:1%;">
                        <col>
                        <thead class="table-dark">
                            <tr>
                                <th>Remove</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if($members){
                            foreach ($members as $m) { ?>
                            <tr>
                                <td><input type="checkbox" name="removeid[]" value="<?= $m['userid']?>"></td>
                                <td><?= $m['f_name'] . " " . $m['l_name'];?></td>
                            </tr>
                        <?php } 
                            } else { ?>This project has no members. <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="manageusers">Save Changes</input>
                    </form>
                </div>
                </div>
            </div>
        </div>

    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
