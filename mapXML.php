<?php


// database login
require ('../connect_db.php');

$run = intval($_GET["run"]);

// header to identify output as XML
header("Content-type: text/xml");
echo "<?xml version='1.0' ?>";
echo "<markers>";

if(ISSET($_GET["pet"])){
    $user = intval($_GET["pet"]);
}


// ruturns distance run on a specfied route by a specified pet
function sum_distance($dbc,$run,$user)
{
    $q = 'SELECT wheels.wheel, wheels.username,
    SUM(readings.distance)
    FROM wheels,readings
    WHERE wheels.wheel=readings.wheel AND readings.run = '.$run.' AND wheels.wheel = "'.$user.'"
    GROUP BY wheels.wheel,wheels.username';
 
    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        $distance = mysqli_fetch_array($r, MYSQLI_ASSOC);
        return $distance["SUM(readings.distance)"];
    }
    else
    {
        echo '<p>'.mysqli_error($dbc).'</p>';
    }
}

// appears to create data for laps complete table
function sum_distance_all($dbc,$run,$routeLength)
{
    $q = 'SELECT animals.wheel, animals.name, animals.breed,
    SUM(readings.distance), wheels.run
    FROM animals,readings,wheels
    WHERE animals.wheel=readings.wheel AND animals.wheel=wheels.wheel AND readings.run = '.$run.'
    GROUP BY animals.name
    ORDER BY SUM(readings.distance) DESC';

    $r = mysqli_query($dbc,$q);
    $a = array([],[],[],[]);

    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
        {
            array_push($a[0],$row["SUM(readings.distance)"]%$routeLength);
            array_push($a[1],$row["name"]);
            array_push($a[2],$row["breed"]);
            array_push($a[3],$row["run"]);
        }
    }
    else {echo '<p>'.mysqli_error($dbc).'</p>';
    }
    return $a;
}

// fetch route length from database
function route_length($dbc,$run)
{
    $q = 'SELECT distance FROM runs WHERE id = ' . $run;

    $r = mysqli_query($dbc,$q);

    if($r)
    {
        $routeLength = mysqli_fetch_array($r, MYSQLI_ASSOC);
        return $routeLength["distance"];
    }
    else
    {
        echo '<p>'.mysqli_error($dbc).'</p>';
    }
}

// distance travelled on specified route by specified wheel
$distance = sum_distance($dbc,$run,$user);

// length of route
$routeLength = route_length($dbc,$run);

// array containing distance travelled on specified route by all rodents
$distanceAll = sum_distance_all($dbc,$run,$routeLength);

// number of laps completed
$numComplete = $distance / $routeLength;

// fraction of current lap run
$lapFraction = $numComplete - floor($numComplete);

// length of current lap run
$lapDistance = $lapFraction * $routeLength;



// 
function main($dbc, $run, $lapDistance, $user)
{
    
    // query the route coordinates and push to an array    
    $routeArray = array([],[],[]);
    
    $q = 'SELECT lat, lng, distance
    FROM routes 
    WHERE routeID=' . $run;

    $r = mysqli_query($dbc,$q);
    $toggle = 1;
    
    
    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
        {
            array_push($routeArray[0],$row["distance"]);
            array_push($routeArray[1],$row["lat"]);
            array_push($routeArray[2],$row["lng"]);
        }
    }
    else {echo '<p>'.mysqli_error($dbc).'</p>';
    }


    // query current lap distance for each pet and pushto an array
    $runDistArray = array([],[]);

    $q = 'SELECT readings.wheel, MOD(SUM(readings.distance),runs.distance) as 
    runDist FROM readings, runs WHERE readings.run = '.$run.' AND readings.run = runs.id GROUP BY wheel ORDER BY wheel';
    
    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
        {
            array_push($runDistArray[0],$row["wheel"]);
            array_push($runDistArray[1],$row["runDist"]);
        }
    }
    else {echo '<p>'.mysqli_error($dbc).'</p>';
    }
    
    // query which pets are currently on the selected run and push to an array
    $petsOnRun = array([],[],[]);
    

    $q = 'SELECT wheels.wheel, animals.name, animals.breed FROM wheels, animals 
    WHERE wheels.wheel = animals.wheel AND wheels.run = '.$run;
    
    
    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
        {
            array_push($petsOnRun[0],$row["wheel"]);
            array_push($petsOnRun[1],$row["name"]);
            array_push($petsOnRun[2],$row["breed"]);
        }
    }
    else {echo '<p>'.mysqli_error($dbc).'</p>';
    }


    // splines showing completed and incompleted stages on the active run
    for ($x = 0; $x < count($routeArray[0]); $x++) {
        if($routeArray[0][$x]<$lapDistance){
            echo "<points1 lat=\"" .$routeArray[1][$x]. "\" lng=\"" .$routeArray[2][$x]. "\"/>";
        }
        elseif($toggle==1){
            $p1_lat = $routeArray[1][$x-1];
            $p1_lng = $routeArray[2][$x-1];
            $p3_lat = $routeArray[1][$x];
            $p3_lng = $routeArray[2][$x];
            $factor = distFactor($routeArray[0][$x-1],$lapDistance,$routeArray[0][$x]);
            $p2_lat = round($p1_lat + $factor * ($p3_lat - $p1_lat),4);
            $p2_lng = round($p1_lng + $factor * ($p3_lng - $p1_lng),4);

            echo "<points1 lat=\"" .$p2_lat. "\" lng=\"" .$p2_lng. "\"/>";
            echo "<points2 lat=\"" .$p2_lat. "\" lng=\"" .$p2_lng. "\"/>";


            $toggle = 0;
        }
        else{
            echo "<points2 lat=\"" .$routeArray[1][$x]. "\" lng=\"" .$routeArray[2][$x]. "\"/>";
        }
    }


    // position of each pet currently on this run
    for ($x = 0; $x < count($petsOnRun[0]); $x++) {
        $u = array_search($petsOnRun[0][$x],$runDistArray[0],true);
        $lapDistance = $runDistArray[1][$u];
        $y = 0;
    
        while($routeArray[0][$y] <= $lapDistance) {
            $y++;
        }

    
        $p1_lat = $routeArray[1][$y-1];
        $p1_lng = $routeArray[2][$y-1];
        $p3_lat = $routeArray[1][$y];
        $p3_lng = $routeArray[2][$y];
    
        $factor = distFactor($routeArray[0][$y-1],$lapDistance,$routeArray[0][$y]);
        $p2_lat = round($p1_lat + $factor * ($p3_lat - $p1_lat),4);
        $p2_lng = round($p1_lng + $factor * ($p3_lng - $p1_lng),4);
        echo "<markerG username=\"" .$petsOnRun[1][$x]. "\" breed=\"" .$petsOnRun[2][$x]. "\" lat=\"" .$p2_lat. "\" lng=\"" .$p2_lng. "\"/>";
    }
    
    // position of selected pet
    $u = array_search(strval($user),$runDistArray[0],true);
    $lapDistance = $runDistArray[1][$u];
    $y = 0;

    while($routeArray[0][$y] <= $lapDistance) {
        $y++;
    }
    
    $p1_lat = $routeArray[1][$y-1];
    $p1_lng = $routeArray[2][$y-1];
    $p3_lat = $routeArray[1][$y];
    $p3_lng = $routeArray[2][$y];

    $factor = distFactor($routeArray[0][$y-1],$lapDistance,$routeArray[0][$y]);
    $p2_lat = round($p1_lat + $factor * ($p3_lat - $p1_lat),4);
    $p2_lng = round($p1_lng + $factor * ($p3_lng - $p1_lng),4);
    echo "<marker lat=\"" .$p2_lat. "\" lng=\"" .$p2_lng. "\"/>";


}

function distFactor($dist1,$dist2,$dist3){
    if($dist1!=$dist3){
        return $factor = ($dist2 - $dist1) / ($dist3 - $dist1);
    }
    else{
        return $factor = 0;
    }
    
}

main($dbc,$run,$lapDistance,$user);

// query and set map style

function mapType($dbc, $run){

    $q = 'SELECT mapType FROM runs WHERE id = ' . $run;

    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);

        echo "<mapType style=\"" .$row["mapType"]. "\" />";
    }
    else
    {
        echo '<p>'.mysqli_error($dbc).'</p>';
    }
}


mapType($dbc, $run);


echo "</markers>";

?>