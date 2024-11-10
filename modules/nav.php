<?php
    if(!isset($_SESSION['privilege'])){
        $_SESSION['privilege'] = '';
    }
?>
    
<?php function get_navbar() { ?>
    <nav class="navbar navbar-expand-lg bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="SDSTaskSite-main/img/logo.png" alt="SDS Logo" height='33px'>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item px-3">
                        <a class="nav-link active text-white" aria-current="page" href="SDSTaskSite-main/index.php">
                            <i class="bi bi-kanban"></i>
                            <span>&nbsp Projects</span>
                        </a>
                    </li> 
                
                <?php if($_SESSION['privilege'] == 'Admin'){ ?>

                        <li class="nav-item px-3">
                            <a class="nav-link text-white" href="../users.php">
                                <i class="bi bi-people-fill"></i>
                                <span>&nbsp Users</span>
                            </a>
                        </li>
                <?php } ?>

            </ul>
            <ul class="navbar-nav">
                    
                    <li class="nav-item px-3">
                        <a class="nav-link clickable text-white" href="SDSTaskSite-main/services/logout.php"> 
                            <i class="bi bi-box-arrow-right" style='color: red; font-size: 130%'></i>
                        </a>
                    </li>
            </ul>
        </div>
    </div>
    </nav>
<?php } ?>