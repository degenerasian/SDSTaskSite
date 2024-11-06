<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        // Get general task details
        $taskid = mysqli_escape_string($con, $_GET['taskid']);
        $query = "SELECT tasks.*, users.userid, users.f_name, users.l_name
                    FROM tasks, users
                    WHERE tasks.taskid = ?
                    AND users.userid = tasks.created_by";
            
            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $taskid);
            $stmt->execute();
            $result = $stmt->get_result();
            $task = mysqli_fetch_assoc($result);
            $created_on = date("F j, Y", strtotime($task['created_on']));

        // Get assignee based details
        $query = "SELECT users.userid, users.f_name, users.l_name
                    FROM tasks
                    INNER JOIN assignee ON assignee.taskid = tasks.taskid
                    INNER JOIN users ON assignee.userid = users.userid 
                    WHERE tasks.taskid = ?";

            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $taskid);
            $stmt->execute();
            $results = $stmt->get_result();
            $assignees = array();
            while ($row = mysqli_fetch_assoc($results)) {
                $assignees[] = $row;
            }

        $query = "SELECT users.userid, users.f_name, users.l_name
                    FROM users
                    EXCEPT
                    SELECT users.userid, users.f_name, users.l_name
                    FROM tasks
                    INNER JOIN assignee ON assignee.taskid = tasks.taskid
                    INNER JOIN users ON assignee.userid = users.userid 
                    WHERE tasks.taskid = ?";

            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $taskid);
            $stmt->execute();
            $results = $stmt->get_result();
            $n_assignees = array();
            while ($row = mysqli_fetch_assoc($results)) {
                $n_assignees[] = $row;
            }
    ?>

    <body class="bg-body-tertiary">
        <div class="container-fluid px-5 py-3">
            <div class="row">
                <div class="col mt-4">
                    <h1><strong><?= $task['task_name'] ?></strong></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <p>Created by <?= $task['f_name'] . " " . $task['l_name'];?> on <?= $created_on?></p>
                </div>
            </div>
            <div class="row">
                <hr>
                <div class="col-12 col-lg-7 col-xl-7 mb-4 me-2 shadow rounded bg-white p-4">
                    <p><?= $task['task_desc']?></p>
                </div>
                <div class="col-12 col-lg-4 col-xl-3 px-4 mb-4 py-3 bg-white shadow rounded">
                    <h4>Assignees</h4>
                    <hr>
                    <?php if($assignees) { ?>
                        <table class="table table-hover table-borderless">
                            <tbody>
                            <?php foreach($assignees as $a) { ?>
                                <tr>
                                    <td style="display:none;"><?php echo $a['userid']?></td>
                                    <td><?php echo $a['f_name'] . " " . $a['l_name'];?></td>
                                    <td><button type="button" class="btn btn-danger removebtn" data-bs-toggle="modal" data-bs-target="#removeModal">Remove</button></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else {?> 
                        <p>No users assigned to this task.</p>
                    <?php } ?>
                    <button type="button" class="btn btn-warning align-middle" data-bs-toggle="modal" data-bs-target="#inviteModal">Manage</button>
                </div> 
            </div>
        </div>  


        <!--    Remove Modal    -->
        <div class="modal fade" id="removeModal" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="removeModalLabel">Remove this assignee?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <form action="services/deleteassignee.php?taskid=<?= $taskid?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="remove_id" id="remove_id">
                    This can't be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="removedata" class="btn btn-danger">Remove</button>
                    </form>
                </div>
                </div>
            </div>
        </div>

        <!--    Invite Modal    -->
        <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="inviteModalLabel">Manage Assignees</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Add to <?= $task['task_name'];?></h5>
                    <form method="POST" action="services/manageassignees.php?taskid=<?= $taskid?>">
                    <?php if($n_assignees){ ?>
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
                                <?php foreach ($n_assignees as $n) { ?>
                                    <tr>
                                        <td><input type="checkbox" name="addid[]" value="<?= $n['userid']?>"></td>
                                        <td><?= $n['f_name'] . " " . $n['l_name'];?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>No assignees available. 
                    <?php } ?>
                    <hr>
                    <h5>Remove from <?= $task['task_name'];?></h5>
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
                        if($assignees){
                            foreach ($assignees as $m) { ?>
                            <tr>
                                <td><input type="checkbox" name="removeid[]" value="<?= $m['userid']?>"></td>
                                <td><?= $m['f_name'] . " " . $m['l_name'];?></td>
                            </tr>
                        <?php } 
                            } else { ?>This project has no assignees. <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="manageassignees">Save Changes</input>
                    </form>
                </div>
                </div>
            </div>
        </div>
        
        <!-- For assignee delete -->
        <script>
            $(document).ready(function (){
                $('.removebtn').on('click', function() {
                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function() {
                        return $(this).text();
                    }).get();

                    console.log(data);
                    $('#remove_id').val(data[0]);
                });
            });
        </script>
    </body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
