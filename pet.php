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
                if ( isset ( $_SESSION['pet']))
                {
                    echo "?pet=".$_SESSION['pet'];
                }            
            ?>">Run</a></li>
            <li class="active"><a href="stats.php">Stats</a></li>

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
            <form id="petB" align="center">

                <select class="form-control" name="petB" onchange="this.form.submit()">

                    <option value="" disabled selected>-- select pet --</option>

                    <?php

                        $petB = 0;


                        if ( isset ( $_GET['petB'])){
                            $petB=$_GET['petB'];
                        }

                        // database login
                        require ('../connect_db.php');

                        // query pet names from database and create selection list
                        function petList($dbc,$petB)
                        {


                            $q = 'SELECT animals.id, animals.name FROM animals, wheels WHERE wheels.wheel = animals.wheel AND wheels.cyber = 0';


                            $r = mysqli_query($dbc,$q);
                        
                            if($r)
                            {
                                while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
                                
                                {
                                    // if($row["id"]==intval($petB))
                                    if($row["id"]==$petB)
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

                        petList($dbc,$petB);

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


            <table class="table">
                <thead>
                    <tr>
                    <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                <?php
            
                    $petB = 0;

                    if ( isset ( $_GET['petB'])){
                        $petB=$_GET['petB'];
                    }    
                    
                    function petData($dbc,$petB)
                                        {

                                            $q = 'SELECT * FROM animals WHERE id ='.$petB;


                                            $r = mysqli_query($dbc,$q);
                                        
                                            if($r)
                                            {
                                                $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
                                                
                                                echo "<tr><td>Breed</td><td>".$row["breed"]."</td><td></td></tr>";
                                                echo "<tr><td>Home Country</td><td>".$row["country"]."</td>
                                                <td> <img src=\"flags/".strtolower($row["country"]).".png\" height=\"20\" width=\"20\" ></td></tr>";
                                                echo "<tr><td>Teammates</td><td>x</td><td>x</td></tr>";
                                                echo "<tr><td>Date of Birth</td><td>x</td><td>x</td></tr>";
                                                echo "<tr><td>Star Sign</td><td>x</td><td>x</td></tr>";
                                                echo "<tr><td>Total Distance</td><td>x</td><td>x</td></tr>";
                                                echo "<tr><td>Max Speed</td><td>x</td><td>x</td></tr>";
                                                echo "<tr><td>Wheel #</td><td>".$row["wheel"]."</td><td></td></tr>";    
                                                
                                            }
                                            else {echo '<p>'.mysqli_error($dbc).'</p>';
                                            }
                                        }

                                        petData($dbc,$petB);

                ?>
                



                </tbody>
            </table>
    </div>
    <div class="col-sm-6">
    
        <?php

            $petB = 0;

            if ( isset ( $_GET['petB'])){
                $petB=$_GET['petB'];
            }

            echo "<img src=\"portraits/".$petB.".jpg\" class=\"img-thumbnail\" alt=\"portrait\">";
        ?>




        <!-- <img src="portraits/22.jpg" class="img-thumbnail" alt="portrait"> -->
    </div>


</div>


<div class="container">
    <p></p>
    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img class="img-responsive center-block img-rounded" alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
    <p></p>
    <p class="text-center">This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>
</div>








  </body>
    

