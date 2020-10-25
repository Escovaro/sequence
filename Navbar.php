<!-- Navbar1 -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.html"><img src="img/Schriftlogo_mitlogo_weiss_schmal.png"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" 
            data-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="konzept.html">konzept</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="tutorials.html">tutorials</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nach_login/new_project/neues_projekt1.php">new_sequence</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registrierung.php">register</a>
                        </li>
                        <?php 
                        /* Check ob login button notwendig */
                        session_start();
                        $link1 ="login.php";
                            if(!isset($_SESSION['mitarbeiterid'])) {
                                echo "<li>"."<a class='nav-link' href='$link1'>"."login"."</a>"."</li>";
                        }               
                        /* Check ob logout button notwendig */                        
                        $link2 ="nach_login/logout.php";
                            if(isset($_SESSION['mitarbeiterid'])) {
                                echo "<li>"."<a class='nav-link logout' href='$link2'>"."logout"."</a>"."</li>";
                        }              
                        ?>
                </ul>
            </div>
        </div> 
    </nav>