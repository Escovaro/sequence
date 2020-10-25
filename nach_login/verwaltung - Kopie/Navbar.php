<!-- Navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.html"><img src="../../img/Schriftlogo_mitlogo_weiss_schmal.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" 
        data-target="#navbarResponsive">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../../konzept.html">konzept</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../tutorials.html">tutorials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../new_project/neues_projekt1.php">new_sequence</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../registrierung.php">register</a>
                    </li>
                    <?php 
                        /* Check ob login button notwendig */
                        session_start();
                        $link1 ="../../login.php";
                            if(!isset($_SESSION['mitarbeiterid'])) {
                                echo "<li>"."<a class='nav-link' href='$link1'>"."login"."</a>"."</li>";
                        }               
                        /* Check ob logout button notwendig */                        
                        $link2 ="../logout.php";
                            if(isset($_SESSION['mitarbeiterid'])) {
                                echo "<li>"."<a class='nav-link logout' href='$link2'>"."logout"."</a>"."</li>";
                        }              
                    ?>
            </ul>
        </div>
    </div> 
</nav>

<!-- Zwischenbar -->
<nav class="navbar navbar-expand-md navbar-light bg-light sticky-top">
    <div class="container-fluid">     
        <!-- SCHRITT-ZURÜCK-BUTTON
        <div class=" " id="navbarResponsive">
            <ul class="navbar-nav d-flex justify-content-between">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Schritt zurück</a>
                    </li>
            </ul>
        </div-->
        <button class="navbar-toggler float-left" type="button" data-toggle="collapse" 
        data-target="#navbarResponsive2">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive2">
            <ul class="navbar-nav d-flex justify-content-between">      
                    <li class="nav-item">
                        <a class="nav-link" href="Kundenverwaltung.php">Kundenverwaltung</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" href="mitarbeiterverwaltung.php">Mitarbeiterverwaltung</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="Projektverwaltung.php">Projektverwaltung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="subtaskverwaltung.php">Subtaskverwaltung</a>
                    </li>
                    <li class="nav-item">
                        <form class="form-inline md-form form-sm mt-0">
                        <i class="icon fas fa-search mt-1" aria-hidden="true"></i>
                        <input class="form-control" type="text" placeholder="In Tabelle Suchen" aria-label="Search">
                                
                        </form>
                    </li>
            </ul>            
        </div>
    </div> 
</nav>