<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>Hackminster - Stats</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<?php // update session variable if new wheel selected

    session_start();

    if ( isset ( $_GET['wheel']))
    {
         $_SESSION['wheel'] = $_GET['wheel'];

    }

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
            <li class="active"><a href="stats.php">Stats</a></li>
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
<h2>Vital Stats</h2>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <!-- form for selecting pet(s) -->
            <form id="wheel" align="center">

                <select class="form-control" name="wheel" onchange="this.form.submit()">

                    <option value="" disabled selected>-- select pet --</option>

                    <?php

                        $wheel = 0;

                        if(isset($_SESSION['wheel'])){
                            $wheel=$_SESSION['wheel'];
                        }

                        // database login
                        require ('../connect_db.php');

                        // query pet names from database and create selection list
                        function petList($dbc,$wheel)
                        {
                            $q = 'SELECT wheel, teamName FROM wheels';
                            $r = mysqli_query($dbc,$q);
                        
                            if($r)
                            {
                                while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
                                
                                {
                                if($row["wheel"]==$wheel)
                                {
                                    $select = "selected";
                                }
                                else
                                {
                                    $select = "";
                                }
                                    echo "<option value=" .$row["wheel"]. " " .$select.
                                    ">" . $row["teamName"] . "</option>"; 
                                }
                            }
                            else {echo '<p>'.mysqli_error($dbc).'</p>';
                            }
                        }

                        petList($dbc,$wheel);

                    ?>

                </select>
                
            </form>

        </div>

        <div class="col-sm-4"></div>
    </div>
</div>



<div class="container">
    <p></p>
    <div class="row">
    
        <!-- <div class="col-sm-8 rounded" style="background-color:#f0f0f5"> -->
        <div class="col-sm-8">
            <h4 align="center">Distance</h4>
            <!-- <h3 align="center">Hourly</h3> -->

            <div id="chart-container">
                <canvas id="24hrChart"></canvas>
            </div>

            <!-- <h3 align="center">Daily</h3> -->

            <div id="chart-container">
                <canvas id="weekChart"></canvas>
            </div>

            <p></p>

            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>    

            <script>


                <?php
                    if(isset($_SESSION["wheel"])){
                        $wheel=$_SESSION["wheel"];
                        echo "var wheel = ".$wheel;
                    }
                ?>

                // download xml file
                downloadUrl('chartXML.php?pet='+wheel, function(data) {
                    
                    // create data objects from XML file
                    var xml = data.responseXML;
                    var distYest = xml.documentElement.getElementsByTagName('previousDay');
                    var distToday = xml.documentElement.getElementsByTagName('thisDay');
                    var distLastWeek = xml.documentElement.getElementsByTagName('lastWeek');
                    var distThisWeek = xml.documentElement.getElementsByTagName('thisWeek');
                    
                    var todayDist = [];

                    for (var i = 0; i < distToday.length; i++) {
                        var dist = parseFloat(distToday[i].getAttribute('dist'));
                        todayDist.push(dist);
                    };

                    var yesterdayDist = [];

                    for (var i = 0; i < distYest.length; i++) {
                        var dist = parseFloat(distYest[i].getAttribute('dist'));
                        yesterdayDist.push(dist);
                    };

                    var lastWeekDist = [];

                    for (var i = 0; i < distLastWeek.length; i++) {
                        var dist = parseFloat(distLastWeek[i].getAttribute('dist'));
                        lastWeekDist.push(dist);
                    };

                    var thisWeekDist = [];

                    for (var i = 0; i < distThisWeek.length; i++) {
                        var dist = parseFloat(distThisWeek[i].getAttribute('dist'));
                        thisWeekDist.push(dist);
                    };

                    //
                    // hourly distance over two days
                    //

                    // set in minimum y-axis value to 0
                    Chart.scaleService.updateScaleDefaults('linear', {
                        ticks: {
                            min: 0
                        }
                    });

                    var ctx = document.getElementById('24hrChart').getContext('2d');

                    var hourLabels = [];

                    for(n = 0; n < 24; n++) {
                            hourLabels.push(n+1);
                    }

                    var options = {
                        legend: {
                            display: true
                        },
                        title: {
                            display: true,
                            text: 'Hourly Run Distance [m]'
                        },

                        scales: {
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Distance @ x hours'
                                }

                            }]
                        }
                    };

                    var chart = new Chart(ctx, {

                        type: 'bar',

                        data: {
                            labels: hourLabels,
                            datasets: [
                                {
                                label: 'Yesterday',
                                backgroundColor: 'rgba(255, 204, 153)',
                                hoverBackgroundColor: 'rgba(255, 153, 0)',
                                borderColor: 'rgba(255, 255, 255)',
                                data: yesterdayDist
                                },
                                {
                                label: 'Today',
                                backgroundColor: 'rgba(255, 0, 0)',
                                hoverBackgroundColor: 'rgba(255, 0, 0)',
                                borderColor: 'rgba(255, 255, 255)',
                                data: todayDist
                                }
                            ]
                        },
                        
                        options: options
                    });

                    //
                    // daily distance over one fortnight
                    //

                    var ctx = document.getElementById('weekChart').getContext('2d');

                    var options = {
                        legend: {
                            display: true
                        },
                        title: {
                            display: true,
                            text: 'Daily Run Distance [m]'
                        },
                    };

                    var chart = new Chart(ctx, {

                        type: 'bar',

                        data: {
                            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                            datasets: [
                                {
                                label: 'Last Week',
                                backgroundColor: 'rgb(51, 204, 204)',
                                borderColor: 'rgb(255, 255, 255)',
                                data: lastWeekDist
                                },
                                {
                                label: 'This Week',
                                backgroundColor: 'rgb(51, 51, 204)',
                                borderColor: 'rgb(255, 255, 255)',
                                data: thisWeekDist
                                }
                            ]

                        },
                        
                        options: options
                        
                    });
                
                });




                // functions for loading xml file
                function downloadUrl(url, callback) {
                    var request = window.ActiveXObject ?
                        new ActiveXObject('Microsoft.XMLHTTP') :
                        new XMLHttpRequest;

                    request.onreadystatechange = function() {
                    if (request.readyState == 4) {
                        request.onreadystatechange = doNothing;
                        callback(request, request.status);
                    }
                    };

                    request.open('GET', url, true);
                    request.send(null);
                }

                function doNothing() {}

            </script>
        </div>

        <!-- <div class="col-sm-4" style="background-color:#e0e0eb"> -->
        <div class="col-sm-4">   

            <div class="jumbotron text-center">
                <!-- speedo -->
                <h4 align="center">Speedo</h4>

                <p></p>

                <h5 align="center">24 Hour Max</h5>

                <div style="text-align:center;">
                <?php
                    $name = 'neddleToday';
                    include 'includes/speedo.php';
                ?>
                </div>

                <p></p>

                <h5 align="center">Record</h5>

                <div style="text-align:center;">
                <?php
                    $name = 'neddleRecord';
                    include 'includes/speedo.php';
                ?>
                </div>




                <script>
                
                
                
                    var neddle = document.getElementById('neddle');
                    var neddleRecord = document.getElementById('neddleRecord');
 
                    function speedoReading(neddle,speed){
                        var angle = 30*speed-150;
                        neddle.setAttribute('transform','rotate(' + angle + ',100,100)');
                    }


                    
                    // query max speed for wheel
                    <?php

                        function speed24hrMax($dbc,$wheel)
                        {
                            $q = 'SELECT MAX(speed) AS speed24hrMax FROM readings WHERE wheel = '.$wheel.
                            // ' AND hourDate BETWEEN \'2019-11-05 21:00:00\' AND \'2019-11-06 00:00:00\'';
                            ' AND hourDate BETWEEN \''.date("Y\-m\-d H\:i\:s",time()-3600*24).'\' AND \''.date("Y\-m\-d H\:i\:s",time()).'\'';

                            $r = mysqli_query($dbc,$q);
                            if($r)
                            {
                                while($result = mysqli_fetch_array($r, MYSQLI_ASSOC))
                                
                                {
                                    echo "speed24hrMax = " .$result["speed24hrMax"]; 
                                }
                            }
                            else {echo '<p>'.mysqli_error($dbc).'</p>';
                            }
                        }
                        speed24hrMax($dbc,$wheel);
                    ?>

                    <?php

                        function speedRecord($dbc,$wheel)
                        {
                            $q = 'SELECT MAX(speed) AS speedRecord FROM readings WHERE wheel = '.$wheel;
                            $r = mysqli_query($dbc,$q);

                            if($r)
                            {
                                while($result = mysqli_fetch_array($r, MYSQLI_ASSOC))
                                
                                {
                                    echo "speedRecord = " .$result["speedRecord"]; 
                                }
                            }
                            else {echo '<p>'.mysqli_error($dbc).'</p>';
                            }
                        };

                        speedRecord($dbc,$wheel);

                    ?>

                    speedoReading(neddleToday,speed24hrMax);
                    speedoReading(neddleRecord,speedRecord);

                    



                </script>
            
            </div>
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
    

