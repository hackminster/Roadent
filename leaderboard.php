<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>Hackminster - Leaderboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

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
            <li class="active"><a href="leaderboard.php">Leaderboard</a></li>
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
  <h2>Leaderboard</h2>
</div>


<div class="container">
    <div class="row">
  
        <div class="col-sm-6">
            <h2 align="center">Overall</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th>Rank</th>
                    <th>Pets</th>
                    <th>Breed</th>
                    <th>Home</th>
                    <th></th>
                    <th>Dist. (km)</th>
                    </tr>
                </thead>
                <tbody>


                    <?php
                    // database login
                    require ('../connect_db.php');

                    // show 'runs' db function
                    function top10_dist($dbc)
                    {
                        $q = 'SELECT wheels.teamName, wheels.breed, wheels.country, wheels.cyber, ROUND(SUM(readings.distance)/1000, 1)
                        FROM readings, wheels 
                        WHERE wheels.wheel=readings.wheel
                        GROUP BY readings.wheel 
                        ORDER BY SUM(readings.distance) DESC LIMIT 10';
                        $r = mysqli_query($dbc,$q);
                        
                        if($r)
                        {
                            $rank = 1;
                            while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
                            {
                                echo "<tr><td align=\"center\">". $rank++ .
                                "</td><td>" . $row["teamName"]. "</td><td>";

                                if($row["cyber"]==1){
                                    echo "<kbd>". ucwords($row["breed"]) ."</kbd>";
                                    
                                }
                                else{
                                    echo ucwords($row["breed"]);
                                }
                                 
                                 echo "</td><td>" 
                                . $row["country"] . "</td><td> <img src=\"flags/"
                                . strtolower($row["country"]). ".png\" height=\"20\" width=\"20\" ></td><td align=\"center\">"
                                . $row["ROUND(SUM(readings.distance)/1000, 1)"]. " </td></tr>";
                            }


                        }
                        else {echo '<p>'.mysqli_error($dbc).'</p>';
                        }
                    }
                    top10_dist($dbc);
                    ?>


                </tbody>
            </table>

        </div>


        
        <div class="col-sm-6">

            <h2 align="center">Breed</h2>

            <form>
                <select class="form-control" name="breed" onchange="this.form.submit()">
                    <option value="" disabled selected>-- select breed --</option>
                    <option value="gerbil">Gerbil</option>
                    <option value="hamster">Hamster</option>
                    <option value="dwarf hamster">Dwarf Hamster</option>
                    <option value="rat">Rat</option>
                </select>
            </form>


            <p></p>

            <table class="table table-striped">
                <thead>
                    <tr>
                    <th>Rank</th>
                    <th>Pets</th>
                    <th>Breed</th>
                    <th>Home</th>
                    <th>Flag</th>
                    <th>Dist. (km)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // database login
                require ('../connect_db.php');

                // show 'runs' db function
                function breed_dist($dbc,$breed)
                {
                    $q = 'SELECT wheels.teamName, wheels.breed, wheels.country, wheels.cyber, ROUND(SUM(readings.distance)/1000, 1)
                    FROM readings, wheels 
                    WHERE wheels.wheel=readings.wheel AND wheels.breed = "'.$breed.'"
                    GROUP BY readings.wheel 
                    ORDER BY SUM(readings.distance) DESC LIMIT 10';
                    $r = mysqli_query($dbc,$q);
                    
                    if($r)
                    {
                        $rank = 1;
                        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
                        {
                            echo "<tr><td align=\"center\">". $rank++ .
                            "</td><td>" . $row["teamName"]. "</td><td>";
                            
                            if($row["cyber"]==1){
                                echo "<kbd>". ucwords($row["breed"]) ."</kbd>";
                                
                            }
                            else{
                                echo ucwords($row["breed"]);
                            }
                            
                            echo "</td><td>" 
                            . $row["country"] . "</td><td> <img src=\"flags/"
                            . strtolower($row["country"]). ".png\" height=\"20\" width=\"20\" ></td><td align=\"center\">"
                            . $row["ROUND(SUM(readings.distance)/1000, 1)"]. " </td></tr>";
                        }
                    }
                    else {echo '<p>'.mysqli_error($dbc).'</p>';
                    }
                }

                if(isset($_GET["breed"])){
                    $breed=$_GET["breed"];
                    breed_dist($dbc,$breed);
                }    

                
                ?>

                </tbody>
            </table>
            <p><kbd>Cyber Pets</kbd></p>
        </div>


    </div>
</div>


<div class="container">
    Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a>
</div>

<div class="container">
    <p></p>
    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img class="img-responsive center-block img-rounded" alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
    <p></p>
    <p class="text-center">This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>
</div>


<p></>







