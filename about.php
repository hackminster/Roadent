<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hackminster - About</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php 
    session_start();
?>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="about.php">Hackminster</a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
            <li><a href="run.php<?php
                if ( isset ( $_SESSION['wheel']))
                {
                    echo "?wheel=".$_SESSION['wheel'];
                }            
            ?>">Run</a></li>
            <li><a href="stats.php">Stats</a></li>
            <li><a href="pet.php">Pet Profiles</a></li>

        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item">
                <a class="nav-link" href="https://www.instagram.com/hackminster/">
                <i class="fa fa-instagram"></i> Instagram</a> 
            </li>
        </ul>
    </div>
</nav>


<div class="container">
    <div class="row">
  
        <div class="col-sm-2">
        </div>

        <div class="col-sm-8">
            <img class="img-responsive center-block img-rounded" src="images/hackminsterLogo.png" alt="Logo" style="width:70%">
        </div>

        <div class="col-sm-2">
        </div>

    </div>
    <p></p>
</div>



<div class="jumbotron text-center">
  <h1>Hackminster</h1>
  <p>Amateur electronics hacks from Bedminster, Bristol (UK).</p> 
</div>




<div class="container">
    <div class="row">
  
        <div class="col-sm-2">
        </div>

        <div class="col-sm-8">
            <img class="img-responsive center-block img-rounded" src="images/LegoTank.jpg" alt="Lego Tank" style="width:70%">
        </div>

        <div class="col-sm-2">
        </div>

    </div>
    <p></p>
</div>

<div class="container">
    <p></p>
    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img class="img-responsive center-block img-rounded" alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
    <p></p>
    <p class="text-center">This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>
</div>

</body>
</html>
