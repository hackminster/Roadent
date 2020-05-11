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
else {
    $user = 108;
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



// is this used????
function splines($dbc, $run, $lapDistance)
{
    $q = 'SELECT lat, lng, distance
    FROM routes 
    WHERE routeID=' . $run;

    $r = mysqli_query($dbc,$q);
    $toggle = 1;
    $routeArray = array([],[],[]);
    
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
    for ($x = 0; $x < count($routeArray[0]); $x++) {
        if($routeArray[0][$x]<$lapDistance){
            echo "<points1 lat=\"" .$routeArray[1][$x]. "\" lng=\"" .$routeArray[2][$x]. "\"/>";
        }
        elseif($toggle==1){
            $p1_lat = $routeArray[1][$x-1];
            $p1_lng = $routeArray[2][$x-1];
            $p2_lat = $routeArray[1][$x];
            $p2_lng = $routeArray[2][$x];
            $deltaLat = $p2_lat - $p1_lat;
            $deltaLng = $p2_lng - $p1_lng;
            // echo $p1_lat. "<p> </p>";
            // echo $p1_lng. "<p> </p>";
            // echo $p2_lat. "<p></p>";
            // echo $p2_lng. "<p></p>";
            // echo $deltaLat. "<p></p>";
            // echo $deltaLng. "<p></p>";
            $p1p2DeltaDist = $routeArray[0][$x]-$routeArray[0][$x-1];
            $p1pxDeltaDist = $lapDistance-$routeArray[0][$x-1];
            if($p1pxDeltaDist>0){
                $factor = $p1pxDeltaDist / $p1p2DeltaDist;
                // echo  $factor."<p> </p>";
                // echo "bum";
                echo "<points1 lat=\"" .round(($routeArray[1][$x-1] + $deltaLat*$factor),4). "\" lng=\"" .round(($routeArray[2][$x-1] + $deltaLng*$factor),4). "\"/>";
                echo "<points2 lat=\"" .round(($routeArray[1][$x-1] + $deltaLat*$factor),4). "\" lng=\"" .round(($routeArray[2][$x-1] + $deltaLng*$factor),4). "\"/>";
            }
            else{
                echo "<points1 lat=\"" .$routeArray[1][$x]. "\" lng=\"" .$routeArray[2][$x]. "\"/>";
                echo "<points2 lat=\"" .$routeArray[1][$x]. "\" lng=\"" .$routeArray[2][$x]. "\"/>";
            }

            $toggle = 0;
        }
        else{
            echo "<points2 lat=\"" .$routeArray[1][$x]. "\" lng=\"" .$routeArray[2][$x]. "\"/>";
        }
    }
}









// create points for 'lap run so far' spline
function line1($dbc, $run, $lapDistance)
{
    $q = 'SELECT lat, lng
    FROM routes 
    WHERE routeID=' . $run . ' AND distance < ' . $lapDistance;

    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
        {
            echo "<points1 lat=\"" .$row["lat"]. "\" lng=\"" . $row["lng"] . "\"/>";
        }
    }
    else {echo '<p>'.mysqli_error($dbc).'</p>';
    }
}



// create points for 'remaining section of lap' spline
function line2($dbc, $run, $lapDistance)
{
    $q = 'SELECT lat, lng
    FROM routes 
    WHERE routeID=' . $run . ' AND distance > ' . $lapDistance;

    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
        {
            echo "<points2 lat=\"" .$row["lat"]. "\" lng=\"" . $row["lng"] . "\"/>";
        }
    }
    else {echo '<p>'.mysqli_error($dbc).'</p>';
    }
}



function marker($dbc, $run, $lapDistance){
    
    $q = 'SELECT lat, lng
    FROM routes 
    WHERE routeID=' . $run . ' AND distance > ' . $lapDistance .' ORDER BY id LIMIT 1';

    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
        {
            echo "<marker lat=\"" .$row["lat"]. "\" lng=\"" . $row["lng"] . "\"/>";
        }
    }
    else {echo '<p>'.mysqli_error($dbc).'</p>';
    }
}

splines($dbc,$run,$lapDistance);
// line1($dbc,$run,$lapDistance);
// line2($dbc,$run,$lapDistance);


function markers($dbc, $run, $distanceAll){
    
    for ($x = 0; $x < count($distanceAll[0]); $x++) {
        // echo($distanceAll[3][$x]);

        if(intval($distanceAll[3][$x])==$run){
            $q = 'SELECT lat, lng
            FROM routes 
            WHERE routeID=' . $run . ' AND distance > ' . $distanceAll[0][$x] .' ORDER BY id LIMIT 1';
        
            $r = mysqli_query($dbc,$q);
            
            if($r)
            {
                while($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
                {
                    echo "<markerG username=\"" .$distanceAll[1][$x]. "\" breed=\"" .$distanceAll[2][$x]. "\" lat=\"" .$row["lat"]. "\" lng=\"" . $row["lng"] . "\"/>";
                }
            }
            else {echo '<p>'.mysqli_error($dbc).'</p>';
            }
        }

    
    
    }



}




marker($dbc, $run, $lapDistance); // selected pet marker
markers($dbc, $run, $distanceAll);

// query map type

function mapType($dbc, $run){

    $q = 'SELECT mapType FROM runs WHERE id = ' . $run;

    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);

        // $row ="satellite" ;
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