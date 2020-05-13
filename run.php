<!DOCTYPE HTML>
<html lang="en">
<head>
    <!-- <meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <meta http-equiv="content-type" content="text/html; charset=UTF-8"/> -->
    <title>Hackminster - Run</title>
    <!-- <link rel="stylesheet" href="includes/style.css"> -->


    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>


<?php // update session variable if new pet selected

    session_start();

    if ( isset ( $_GET['pet']))
    {
         $_SESSION['pet'] = $_GET['pet'];
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
            <li class="run"><a href="run.php">Run</a></li>
            <li><a href="stats.php">Stats</a></li>
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
    <h2>Active Run</h2>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <!-- form for selecting pet -->
            <form id="pet" align="center">

                <!-- Selected pet: -->

                <select class="form-control" name="pet" onchange="this.form.submit()">

                    <option value="" disabled selected>-- select pet --</option>

                    <?php

                        $pet = 0;

                        if(isset($_SESSION['pet'])){
                            $pet=$_SESSION['pet'];
                        }

                        // database login
                        require ('../connect_db.php');

                        // query pet names from database and create selection list
                        function petList($dbc,$pet)
                        {
                            $q = 'SELECT wheel, name FROM animals';
                            $r = mysqli_query($dbc,$q);
                        
                            if($r)
                            {
                                while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
                                
                                {
                                if($row["wheel"]==$pet)
                                {
                                    $select = "selected";
                                }
                                else
                                {
                                    $select = "";
                                }
                                    echo "<option value=" .$row["wheel"]. " " .$select.
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

            <p></p>

            <!-- form for selection of run -->

            <form id="run" align="center">
                <!-- Selected route: -->
                <select class="form-control" name="route" onchange="loadDoc(this.value)">

                    <?php

                    if(isset($_GET["pet"])){
                        $pet=$_GET["pet"];
                    }


                    // database login
                    require ('../connect_db.php');

                    function queryRun($dbc,$pet)
                    {
                        $q = 'SELECT run FROM wheels WHERE wheel='.$pet;
                        $r = mysqli_query($dbc,$q);
                        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
                        return $row["run"];          
                    }

                    $run = queryRun($dbc,$pet);

                    // echo"<p></p>";
                    // echo $run;

                    // query runs from database and create selection list
                    function runSelection($dbc,$run)
                    {
                        $q = 'SELECT * FROM runs';
                        $r = mysqli_query($dbc,$q);
                        if($r)
                        {
                            while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
                            {
                                if($row["id"]==$run)
                                {
                                    $select = "selected";
                                }
                                else
                                {
                                    $select = "";
                                }
                                echo "<option value=" . $row["id"]. " " .$select.
                                ">" . $row["name"] . "</option>"; 
                            }
                        }
                        else {echo '<p>'.mysqli_error($dbc).'</p>';
                        }
                    }
                    runSelection($dbc,$run);
                    ?>

                </select>
            </form>

        </div>
        <div class="col-sm-4"></div>
    </div>
</div>






<?php


    if ( isset ( $_SESSION['pet']))
    {
        $pet = $_SESSION['pet'];
    }

?>

<style>
    #map {
        width: 100%;
        height: 400px;
        margin: auto;
        background-color: grey;
    }
</style>


<!-- look-up pet run id and load map -->
<body 
    
    <?php
    if(isset($_SESSION["pet"])){
        $pet=$_SESSION["pet"];
        require ('../connect_db.php');
        $q = 'SELECT run FROM wheels WHERE wheel ='.$pet;
        $r = mysqli_query($dbc,$q);
        if($r){
            $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
            $run = $row["run"];
        }
        echo " onload=\"initMap(".$run.")\" ";
    }
    ?>
>

    <p></p>

<!-- !!!!!!!!!33333333333333333333!!!!!!!!!!!!!!!!!!! -->






<div class="container">
    <div class="row">
        <div class="col-sm-8">
            <p></p>
            <div id="map"></div>
            <p></p>
        </div>
        <div class="col-sm-4">
            <p></p>
            <table class="table table-bordered">
                <tr>
                    <th>Icon</th>
                    <th>Breed</th>
                </tr>
                <tr><td> <img src="http://maps.google.com/mapfiles/kml/paddle/ylw-blank-lv.png"> </td><td>  Gerbil </td></tr>
                <tr><td> <img src="http://maps.google.com/mapfiles/kml/paddle/blu-blank-lv.png"> </td><td>  Rat </td></tr>
                <tr><td> <img src="http://maps.google.com/mapfiles/kml/paddle/grn-blank-lv.png"> </td><td>  Hamster </td></tr>
                <tr><td> <img src="http://maps.google.com/mapfiles/kml/paddle/wht-blank-lv.png"> </td><td>  Dwarf Hamster </td></tr>
            </table>
            
            
            <h2>Runs Completed</h2>

            <table class="table table-striped">
                    <tr>
                        <th>Run</th>
                        <th>Laps</th>
                    </tr>

                <?php

                if(isset($_SESSION["pet"])){
                    $wheel=$_SESSION["pet"];
                    

                    // database login
                    require ('../connect_db.php');

                    // show 'runs' db function
                    function show_laps($dbc,$wheel)
                    {
                        $q = 'SELECT runs.name, ROUND(SUM(readings.distance/runs.distance),1)
                        FROM runs, readings 
                        WHERE runs.id=readings.run AND readings.wheel ='.$wheel.'
                        GROUP BY readings.run';
                        
                        $r = mysqli_query($dbc,$q);
                        
                        if($r)
                        {
                            while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
                            {
                                echo "<tr><td>" . $row["name"]. "</td><td>"
                                . $row["ROUND(SUM(readings.distance/runs.distance),1)"]. "</td></tr>";
                            }
                        }
                        else {echo '<p>'.mysqli_error($dbc).'</p>';
                        }
                    }
                    show_laps($dbc,$wheel);
                    }


                ?>

            </table> 
        </div>
    </div>
</div>






<script>


    function loadDoc(xy) {

        <?php
        if(isset($_GET["pet"])){
            $pet=$_GET["pet"];
        }
        echo "var wheel = ".$pet;
        ?>
    //   var wheel = 101;
    var r = confirm("Confirm change to route: "+ xy);
    if (r == true) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            document.getElementById("demo").innerHTML =
            this.responseText;
            }
        };
        xhttp.open("GET", "routeChange.php?wheel="+wheel+"&route="+xy, true);
        xhttp.send();
        initMap(xy);
    }
    
    }



    // create instant of map
    function initMap(g) {
    var map = new google.maps.Map(document.getElementById('map'), {
    center: new google.maps.LatLng(0, 0),
    zoom: 14,
    });

        
        var run = g;
        <?php
        if(isset($_GET["pet"])){
            $pet=$_GET["pet"];
            echo "var pet = ".$pet;
        }
        ?>
        
        // download xml file 
        downloadUrl('mapXML.php?run='+run+'&pet='+pet, function(data) {
            
            // creat 'points 1 & 2' objects from xml
            var xml = data.responseXML;
            var points1 = xml.documentElement.getElementsByTagName('points1');
            var points2 = xml.documentElement.getElementsByTagName('points2');
            var marker = xml.documentElement.getElementsByTagName('marker');
            var markerG = xml.documentElement.getElementsByTagName('markerG');
            var mapStyle = xml.documentElement.getElementsByTagName('mapType');

            // create bounds object for fit viewport to bounds of route
            var bounds = new google.maps.LatLngBounds();

            var path = [];
            var line2 = [];

            for (var i = 0; i < points1.length; i++) {
                var lat = parseFloat(points1[i].getAttribute('lat'));
                var lng = parseFloat(points1[i].getAttribute('lng'));
                var point = new google.maps.LatLng(lat,lng);
                bounds.extend(point);
                path.push(point);
            };

            for (var i = 0; i < points2.length; i++) {
                var lat = parseFloat(points2[i].getAttribute('lat'));
                var lng = parseFloat(points2[i].getAttribute('lng'));
                var point = new google.maps.LatLng(lat,lng);
                bounds.extend(point);
                line2.push(point);
            };

            for (var i = 0; i < marker.length; i++) {
                var lat = parseFloat(marker[i].getAttribute('lat'));
                var lng = parseFloat(marker[i].getAttribute('lng'));
                var markerPos = new google.maps.LatLng(lat,lng);
            };

            var style = mapStyle[0].getAttribute('style');
            
            // create polyline along path
            var polyline = new google.maps.Polyline({
                map: map,
                path: path,
                strokeColor: "#FF0000",
                strokeOpacity: 1.0,
                strokeWeight: 2
            });

            var polyline2 = new google.maps.Polyline({
                map: map,
                path: line2,
                strokeColor: "#0066ff",
                strokeOpacity: 0.5,
                strokeWeight: 2
            });
            var icon = {
                url: "http://maps.google.com/mapfiles/kml/paddle/ylw-stars.png", // url
                scaledSize: new google.maps.Size(50, 50), // scaled size
            };
            var image = 'http://maps.google.com/mapfiles/kml/shapes/arrow.png';
            var marker1 = new google.maps.Marker({
                position: markerPos,
                map: map,
                icon: icon,
                animation: google.maps.Animation.BOUNCE
            });
            
            var person = {gerbil:"ylw-blank-lv.png", rat:"blu-blank-lv.png", hamster:"grn-blank-lv.png", dwarfhamster:"wht-blank-lv.png"};
            // str = str.replace(/\s+/g, '');
            var png = 'blu-blank-lv.png';
            
            for (var i = 0; i < markerG.length; i++) {
            
            var lat = parseFloat(markerG[i].getAttribute('lat'));
            var lng = parseFloat(markerG[i].getAttribute('lng'));
            var username = markerG[i].getAttribute('username');
            var breed = markerG[i].getAttribute('breed').replace(/\s+/g, '');
            var image = 'http://maps.google.com/mapfiles/kml/paddle/'+person[breed];
            var markerPos = new google.maps.LatLng(lat,lng);
            
            var marker = new google.maps.Marker({
                position: markerPos,
                map: map,
                title: username,
                icon: image,
                animation: google.maps.Animation.DROP
            });
            };

            // zoom map to bounds of route
            map.fitBounds(bounds);

            map.setMapTypeId(style); // roadmap / satellite / terrain
        });

    }


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

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDp05-2tN-FgpIykiE26bK45NkTFAzqF3I&libraries=visualization&callback=initMap">
</script>


<div class="container">
    <p></p>
    <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img class="img-responsive center-block img-rounded" alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a>
    <p></p>
    <p class="text-center">This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.</p>
</div>





</body>
</html>







