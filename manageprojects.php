<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <?php
        //  Check if user is logged in, else redirect to login page
        session_start();
        if(!isset($_SESSION['userid'])){
            header('location: ./login.php');
        }        
        require_once "modules/nav.php";
        get_navbar();
        
        require_once "services/db_config.php";
        $query = "SELECT *
                    FROM projects";
                    
        $stmt = $con->prepare($query);
        $stmt->execute();
        $results = $stmt->get_result();
        $projects = array();

        while ($row = mysqli_fetch_assoc($results)) {
            $projects[] = $row;
        }
        
    ?>
    <body class="bg-body-tertiary">
        <!--main-->
        <div class="container-fluid px-5 py-3">
            <h1>SDS Projects</h1>
            <button type="button" class="btn btn-primary addprojectbtn" data-bs-toggle="modal" data-bs-target="#addprojectModal">Add Project</button>
        </div>
        <div class="col-lg-11 mx-auto">
            <table class="table table-striped scrollable table-bordered align-middle table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Name</th>
                        <th>Name</th>
                    </tr>
                </thead>
                </tbody>
                <?php foreach($projects as $project) {?>
                <tr>
                    <td><?= $project['projectid']?></td>
                    <td><?= $project['project_name']?></td>
                    <td><a class="btn btn-primary" href="editproject.php?projectid=<?php echo $project['projectid']?>"><img class="pe-2" src="img/edit.png" style="width:30px;">Edit</a></td>
                    <td><button type="button" class="btn btn-danger deletebtn" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button></td>
                </tr>
                <?php } ?>
            <tbody>
            </table>
        </div>

<!--    Delete Modal    -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="deleteModalLabel">Delete this project?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
      </div>
      <form action="services/deleteproject.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="delete_id" id="delete_id">
        This can't be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="deletedata" class="btn btn-danger">Delete</button>
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
            <h1 class="modal-title fs-5" id="inviteModalLabel">Manage Members</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h5>Add to <?= $project['project_name'];?></h5>
            <form method="POST" action="services/manageusers.php?projectid=<?= $projectid?>">
            <?php if($n_members){ ?>
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
                    foreach ($n_members as $n) { ?>
                    <tr>
                        <td><input type="checkbox" name="addid[]" value="<?= $n['userid']?>"></td>
                        <td><?= $n['f_name'] . " " . $n['l_name'];?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>No members available. <?php } ?>
            <hr>
            <h5>Remove from <?= $project['project_name'];?></h5>
            <?php if($members) { ?>
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

                        <?php foreach ($members as $m) { ?>
                        <tr>
                            <td><input type="checkbox" name="removeid[]" value="<?= $m['userid']?>"></td>
                            <td><?= $m['f_name'] . " " . $m['l_name'];?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>This project has no members. <?php } ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" name="manageusers">Save Changes</input>
            </form>
        </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function (){
    $('.deletebtn').on('click', function() {
        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function() {
            return $(this).text();
        }).get();

        console.log(data);
        $('#delete_id').val(data[0]);
    });
});

</script>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
