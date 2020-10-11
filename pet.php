<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>Hackminster - Pet Profiles</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>



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
            <li class="active"><a href="pet.php">Pet Profiles</a></li>

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
<h2>Pet Profiles</h2>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <!-- form for selecting pet -->
            <form id="pet" align="center">

                <select class="form-control" name="pet" onchange="this.form.submit()">

                    <option value="" disabled selected>-- select pet --</option>

                    <?php

                        $pet = 0;


                        if ( isset ( $_GET['pet'])){
                            $pet=$_GET['pet'];
                        }

                        // database login
                        require ('../connect_db.php');

                        // query pet names from database and create selection list
                        function petList($dbc,$pet)
                        {


                            $q = 'SELECT animals.id, animals.name FROM animals, wheels WHERE wheels.wheel = animals.wheel AND wheels.cyber = 0';


                            $r = mysqli_query($dbc,$q);
                        
                            if($r)
                            {
                                while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
                                
                                {
                                    // if($row["id"]==intval($pet))
                                    if($row["id"]==$pet)
                                    {
                                        $select = "selected";
                                    }
                                    else
                                    {
                                        $select = "";
                                    }
                                    echo "<option value=" .$row["id"]. " " .$select.
                                    ">" . $row["name"] . "</option>"; 
                                }
                            }
                            else {echo '<p>'.mysqli_error($dbc).'</p>';
                            }
                        }

                        petList($dbc,$pet);

                    ?>

                </select>
                
            </form>

        </div>

        <div class="col-sm-4"></div>
    </div>
</div>

<p></p>

<div class="container">
    <div class="col-sm-6">

        <table class="table table-striped">
            <thead>
                <tr>
                <th>Data</th><th></th><th></th>
                </tr>
            </thead>
            <tbody>
            <?php
        
                $pet = 0;
                $breed = "";
                $genderCode = 0;
                $gender = "";
                $wheel = "";
                $country = "";
                $maxSpeed = 0;
                $totalDistance = 0;
                $cagematesSum = 0;
                $cagemates = "";
                $star = "";
                $dob = "";
         
                function petData($dbc,$pet)
                {
                    global $breed,$country,$genderCode,$star,$wheel,$dob;
                    $q = 'SELECT wheels.breed, animals.dob, wheels.country, animals.gender, animals.star, wheels.wheel 
                    FROM wheels, animals WHERE animals.wheel=wheels.wheel AND animals.id ='.$pet;

                    $r = mysqli_query($dbc,$q);
                
                    if($r)
                    {
                        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
                        
                        $breed = $row["breed"];
                        $dob = $row["dob"];
                        $country = $row["country"];
                        $genderCode = $row["gender"];
                        $star = $row["star"];
                        $wheel = $row["wheel"];

                    }
                    else {echo '<p>'.mysqli_error($dbc).'</p>';
                    }

                }
                
                function cagemates($dbc,$wheel,$pet)
                {
                    global $cagemate, $cagematesSum;
                    $q = 'SELECT name FROM animals WHERE wheel ='.$wheel.' AND NOT id = '.$pet;
                    
                    $r = mysqli_query($dbc,$q);
                    
                    $comma = 0;

                    if($r)
                    {   
                        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
                            if ($comma == 0){
                                $comma = 1;
                            } else {
                                $cagemate .= ", ";
                            }
                            $cagemate .= $row["name"];
                            $cagematesSum = $cagematesSum + 1;

                        }
                        
                    }
                    else {echo '<p>'.mysqli_error($dbc).'</p>';
                    }

                }

                function petPerformance($dbc,$wheel,$cagematesSum)
                {
                    global $maxSpeed, $totalDistance;
                    $q = 'SELECT MAX(speed) AS maxSpeed, SUM(distance) AS totalDistance FROM readings WHERE wheel = '.$wheel;

                    $r = mysqli_query($dbc,$q);
                
                    if($r)
                    {
                        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
                        
                        $maxSpeed = $row["maxSpeed"];
                        $totalDistance = $row["totalDistance"]/($cagematesSum+1);
                        
                    }
                    else {echo '<p>'.mysqli_error($dbc).'</p>';
                    }

                }

                if ( isset ( $_GET['pet'])){
                    $pet=$_GET['pet'];

                    petData($dbc,$pet);

                    petPerformance($dbc,$wheel,$cagematesSum);

                    cagemates($dbc,$wheel,$pet);

                    if($genderCode==0){
                        $gender = "Girl";
                    } else {
                        $gender = "Boy";
                    }

                    $phpdate = strtotime( $dob );

                    
                    $icon = array("gerbil"=>"gerbilIcon", "hamster"=>"hamsterIcon", "dwarf hamster"=>"dwarfHamsterIcon", "rat"=>"ratIcon");
                
                    echo "<tr><td>Breed</td><td>".ucwords($breed)."</td>
                    <td> <img src=\"images/".$icon[$breed].".png\" height=\"20\" width=\"35\" ></td></tr>";
                    echo "<tr><td>Home Country</td><td>".$country."</td>
                    <td> <img src=\"flags/".strtolower($country).".png\" height=\"20\" width=\"20\" ></td></tr>";
                    echo "<tr><td>Cagemates</td><td>".$cagematesSum."</td><td>".$cagemate."</td></tr>";
                    echo "<tr><td>Date of Birth</td><td>".date("jS \of F Y",$phpdate)."</td><td>".date("\(l\)",$phpdate)."</td></tr>";
                    echo "<tr><td>Star Sign</td><td>".$star."</td>
                    <td> <img src=\"starSigns/".strtolower($star).".png\" height=\"20\" width=\"20\" ></td></tr>";
                    echo "<tr><td>Gender</td><td>".$gender."</td><td></td></tr>";
                    echo "<tr><td>Total Distance</td><td>".(round($totalDistance/100/($cagematesSum+1))/10)."km</td><td></td></tr>";
                    echo "<tr><td>Max Speed</td><td>".$maxSpeed."mph</td><td></td></tr>";
                    echo "<tr><td>Wheel #</td><td>".$wheel."</td><td></td></tr>";

                }   
            ?>
     
            </tbody>
        </table>
    </div>

    <div class="col-sm-6">
        <?php

            $pet = 0;

            if ( isset ( $_GET['pet'])){
                $pet=$_GET['pet'];
                echo "<img src=\"portraits/".$pet.".jpg\" class=\"img-thumbnail\" alt=\"portrait\">";
            }

        ?>
    </div>

</div>

<div class="container">
    <p></p>
    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img class="img-responsive center-block img-rounded" alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
    <p></p>
    <p class="text-center">This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>
</div>

</body>
    

