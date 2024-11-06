<?php
    include 'services/db_config.php';
    session_start();
    include "modules/nav.php";
    get_navbar();
?>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
<?php
    $sql = mysqli_query($con, "SELECT project_name, projectid FROM projects");
    $data = $sql->fetch_all(MYSQLI_ASSOC);
?>
<div class="container-fluid">
    <div class="row py-3">
        <form method="POST">
        <div class="row">   
            <div class="col-auto">
                <h5>Select Project:</h5>
            </div>
            <div class="col-auto">
                <select class="form-select" name="projects" onchange='this.form.submit()'>
                    <option value=""></option>
                        <?php foreach ($data as $row): ?>
                            <option value="<?= htmlspecialchars($row['projectid']) ?>">
                                <?= htmlspecialchars($row['project_name']) ?>
                            </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-auto">
                <input class="btn btn-secondary" name='submit' type='submit' value="Create Gantt Chart">
            </form>
        </div>
    </div>
<?php if($_POST['submit']){ ?>
    <div style='margin-top: 10%'></div>

    <!--GANTT CHART -->
    <div class="row mx-2 overflow-scroll">
        <table class='table border border-black'>
            <tr>
                <td colspan = '3' rowspan='2'></td>
    <?php

    $taskids = array();
    $tasknames = array();
    $taskdescs = array();
    $labels = array();
    $categories = array();
    $esttimes = array();
    $fromdates = array();
    $duedates = array();

    $query = "SELECT * FROM tasks WHERE projectid = '".$_POST["projects"]."'";
    $result = mysqli_query($con, $query);
    while ($recordss = mysqli_fetch_assoc($result)){
        array_push($taskids, $recordss['taskid']);
        array_push($tasknames, $recordss['task_name']);
        array_push($taskdescs, $recordss['task_desc']);
        array_push($labels, $recordss['label']);
        array_push($categories, $recordss['label']);
        array_push($esttimes, $recordss['time_est']);
        array_push($fromdates, $recordss['start_date']);
        array_push($duedates, $recordss['due_date']);
    }

    $task_min_date = min($fromdates);
    $min_date = $task_min_date;
    while (date('l', strtotime($min_date)) != "Sunday"){
        $min_date = date('Y-m-d',(strtotime ('-1 day' , strtotime ($min_date) ) ));
    }

    $task_max_date = max($duedates);
    $max_date = $task_max_date;
    while (date('l', strtotime($max_date)) != "Saturday"){
        $max_date = date('Y-m-d', strtotime($max_date. ' +1 day'));
    }
?>

<?php
    $current_month = date('M', strtotime($min_date));
    $new_month = $current_month;
    $date = $min_date;
    $colspan = 0;
    while ($date <= $max_date){
        $new_month = date('M', strtotime($date));
        if ($new_month === $current_month){
            $colspan++;
        } else {
            echo "<td colspan='" .$colspan. "' style='text-align: center; border: 1px solid gray'>" .$current_month. "</td>";
            $current_month = date('M', strtotime($date));
            $colspan = 0;
        }
        $date = date('Y-m-d', strtotime($date. ' +1 day'));
    }
    $colspan++;
    echo "<td colspan='" .$colspan. "' style='text-align: center; border: 1px solid gray'>" .$current_month. "</td>";
    echo "</tr>
            <tr>";

    $current_day = date('l', strtotime($min_date));
    $date = $min_date;
    $colspan = 0;
    $week_num = 1;
    while ($date <= $max_date){
        $current_day = date('l', strtotime($date));
        if ($current_day !=  'Saturday'){
            $colspan++;
        } else {
            $colspan++;
            echo "<td colspan='" .$colspan. "' style='text-align: center; border: 1px solid gray'>Week " .$week_num. "</td>";
            $colspan = 0;
            $week_num++;
        }
        $date = date('Y-m-d', strtotime($date. ' +1 day'));
    }

    echo "</tr>
        <tr>
            <td>Task</td>
            <td>Start Date</td>
            <td>End Date</td>
    ";

    $date = $min_date;
    while($date <= $max_date){
        $day = date('d', strtotime($date));
        echo "<td>".$day."</td>";
        $date = date('Y-m-d', strtotime($date. ' +1 day'));
    }
    echo "</tr>";

    echo "<tr>
        <td colspan = '3'> </td>";

    $date = $min_date;
    while($date <= $max_date){
        $day = date('l', strtotime($date));
        echo "<td>".$day."</td>";
        $date = date('Y-m-d', strtotime($date. ' +1 day'));
    }

    $min_day = date('d', strtotime($min_date));
    $max_day = date('d', strtotime($max_date));
    for ($i = 0; $i < count($taskids); $i++){
        echo "<tr>
            <td>".$taskids[$i]."</td>
            <td>".$fromdates[$i]."</td>
            <td>".$duedates[$i]."</td>";
            $date = $min_date;
            $j = 0;
            while($date <= $max_date){
                if ($date >= $fromdates[$i] && $date <= $duedates[$i]){
                    echo "<td class='bg-secondary border border-black'></td>";
                } else {
                    echo "<td> </td>";
                }
                $date = date('Y-m-d', strtotime($date. ' +1 day'));
                $j++;
            }
        echo "</tr>";
    } 
} ?>
</body>
</html>
