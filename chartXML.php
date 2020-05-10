<?php

// database login
require ('../connect_db.php');

// $refDate=mktime(0,0,0,11,30,2019); // reference date to be switched >>> time()
$refDate=time();


// function outputting distance per hour from the last two days
function hourlyDistance($dbc,$pet,$refDate)
{
    // query yesterdays' data
    $q = 'SELECT HOUR(hourDate), SUM(distance) FROM readings WHERE wheel = '.$pet. 
    ' AND hourDate BETWEEN \''.date("Y-m-d",strtotime("yesterday",$refDate)).'\' AND \''
    .date("Y-m-d",strtotime("today",$refDate)).'\' GROUP BY HOUR(hourDate)';

    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){

            echo "<previousDay hour=\"" .$row["HOUR(hourDate)"]. "\" dist=\"" .$row["SUM(distance)"]. "\"/>";

        }
    }
    else
    {
        echo '<p>'.mysqli_error($dbc).'</p>';
    }

    // query todays' data
    $q = 'SELECT HOUR(hourDate), SUM(distance) FROM readings WHERE wheel = '.$pet. 
    ' AND hourDate BETWEEN \''.date("Y-m-d",strtotime("today",$refDate)).'\' AND \''
    .date("Y-m-d",strtotime("tomorrow",$refDate)).'\' GROUP BY HOUR(hourDate)';

    $r = mysqli_query($dbc,$q);
    
    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){

            echo "<thisDay hour=\"" .$row["HOUR(hourDate)"]. "\" dist=\"" .$row["SUM(distance)"]. "\"/>";

        }
    }
    else
    {
        echo '<p>'.mysqli_error($dbc).'</p>';
    }




}

// function returning distance per day for the last two calendar weeks
function dailyDistance($dbc,$pet,$refDate)
{
    // query last weeks' data
    $q = 'SELECT WEEKDAY(hourDate), SUM(distance) FROM readings WHERE wheel = '.$pet.' AND hourDate BETWEEN \''
    .date("Y-m-d",strtotime("last Week",$refDate)).'\' AND \''.date("Y-m-d",strtotime("this Week",$refDate))
    .'\' GROUP BY WEEKDAY(hourDate)';
 
    $r = mysqli_query($dbc,$q);

    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
            echo "<lastWeek day=\"" .$row["WEEKDAY(hourDate)"]. "\" dist=\"" .$row["SUM(distance)"]. "\"/>";
        }
    }
    else
    {
        echo '<p>'.mysqli_error($dbc).'</p>';
    }

    // query this weeks' data
    $q = 'SELECT WEEKDAY(hourDate), SUM(distance) FROM readings WHERE wheel = '.$pet.' AND hourDate BETWEEN \''
    .date("Y-m-d",strtotime("this Week",$refDate)).'\' AND \''.date("Y-m-d",strtotime("next Week",$refDate))
    .'\' GROUP BY WEEKDAY(hourDate)';
 
    $r = mysqli_query($dbc,$q);

    if($r)
    {
        while($row = mysqli_fetch_array($r, MYSQLI_ASSOC)){
            echo "<thisWeek day=\"" .$row["WEEKDAY(hourDate)"]. "\" dist=\"" .$row["SUM(distance)"]. "\"/>";
        }
        
    }
    else
    {
        echo '<p>'.mysqli_error($dbc).'</p>';
    }

}



if(ISSET($_GET["pet"])){
    $pet = intval($_GET["pet"]);

    // header to identify output as XML
    header("Content-type: text/xml");
    echo "<?xml version='1.0' ?>";
    // start of XML
    echo "<distance>";
    hourlyDistance($dbc,$pet,$refDate);
    dailyDistance($dbc,$pet,$refDate);
    // end of XML
    echo "</distance>";
}
else {
    echo "pet not defined";
}



?>