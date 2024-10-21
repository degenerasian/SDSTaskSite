<?php
    require_once("style.php");
    init_style();
    if(!isset($_SESSION['privilege'])){
        $_SESSION['privilege'] = '';
    }
    function get_navbar() {
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="" alt="SDS Logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item px-3">
                    <a class="nav-link active text-white" aria-current="page" href="#">Projects</a>
                </li>
                <div class="vr text-white"></div>
                <li class="nav-item px-3">
                    <a class="nav-link text-white" href="#">Tasks</a>
                </li>
                <div class="vr text-white"></div>
                <li class="nav-item px-3">
                    <a class="nav-link text-white" href="#">Charts</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item px-3">
                    <a class="nav-link text-white" href="#"><img src="" alt="Profile"></a>
                </li>
                <div class="vr text-white"></div>
                <li class="nav-item px-3">
                    <a class="nav-link text-white" href="#">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php } 
    function get_footer() { ?>
    <p>footer works<p>
<?php
    }
?>