<!DOCTYPE html>
<html lang="en">
<head>
  <title>Hackminster - Home</title>
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
            <li class="active"><a href="index.php">Home</a></li>
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

<div class="jumbotron text-center">
  <h1>Project Gerbil</h1>
  <p>Connecting Snuggles and Gingy to the internet of things...</p> 
</div>




<div class="container">
    <div class="row">
  
        <div class="col-sm-2">
        </div>

        <div class="col-sm-8">
            <img class="img-responsive center-block" src="images/LondonMarathon.png" alt="London Marathon">
            <p></p>
            <p>
                Bristolian gerbils Snuggles and Gingy have run the London Marathon (virtually...). 
                They ran 26.3 miles, but due to some coding issues, Gingy & Snuggles stopped 100m short of the finishing line... 
                click <a href="run.php?pet=100">here</a> to see their latest exploits.
            </p>
        </div>
        
        <div class="col-sm-2">
        </div>


    </div>
</div>

<div class="container">
  <div class="row">

    <div class="col-sm-4">
        <h3>Summary</h3>
        <p> Our family recently grew with the addition of two sister gerbils, Snuggles and Gingy. 
            A week later an exercise wheel was bought, and the Arduino possibilities started to multiply. The objective of this 
            little project was to see how far and fast the gerbils could run; hundreds, or thousands of meters? Could they outrun us bipeds?
        </p>
        <img src="images/Gingy.jpg" class="img-rounded" alt="Gingy" style="width:100%">
        <p></p>
        <p>
            The gerbils’ daily stats are posted at midnight on some clever e-paper, using Pimoroni’s 
            <a href="https://shop.pimoroni.com/products/inky-phat">Inky pHat</a> and a Raspberry Pi. 
            Total distance and the top speed from the last 24 hours are displayed. A handy histogram shows when Snuggles and Gingy 
            have been at their most active.
        </p>
        <img src="images/e-paper.jpg" class="img-rounded" alt="e-paper" style="width:100%">
    </div>

    <div class="col-sm-4">
        <h3>Basic Set-up</h3>
        <p>
            There are a couple of ways to do this, but this project uses an infrared break beam sensor (because I had one handy). 
            Each time the exercise wheel rotates, a Lego brick glued to its periphery breaks the beam and a rotation is logged. 
            Measuring the time between each rotation means speed as well as distance can be calculated. 
            A little extra coding was required to avoid false readings that suggested either Snuggles or her sister had a top 
            speed of 30mph (48kph)!
        </p>
        <img src="images/Set-up.jpg" class="img-rounded" alt="set-up" style="width:100%">
    </div>

    <div class="col-sm-4">
        <h3>Rodent Fitness App</h3>        
        <p>
        Naturally the next step is to develop a fitness app for Snuggles and Gingy, and any whiskered friends that wish to join them. 
            This wacky idea is currently in development.
        </p>
        <span class="label label-success">New!</span>
        <p></p>
        <p>
            Beta pages are now live, with 20 cyber rodents posting data every hour. Use the above navbar to see leaderboards, run maps and stats.
        </p>
        
        <h3>3D Printed Prototype Product</h3>

        <img src="images/Prototype1.jpg" class="img-rounded" alt="set-up" style="width:100%">  
        <p></p>
        <span class="label label-success">New!</span>
        <p></p>      
        <p>Gingy & Snuggles are the only real pets posting data and this has to change! Pictured above is the first attempt to create a plug and
             play product that will enable other fury creatures to join the madness.  </p>
    </div>

  </div>
</div>

<div class="container">
    <p></p>
    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img class="img-responsive center-block img-rounded" alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
    <p></p>
    <p class="text-center">This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>
</div>


</body>
</html>
