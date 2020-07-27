<?php

	// read posted inputs
	$dt = $_POST["datetime"];
	$dist = intval($_POST['distance']);
	$wheel = intval($_POST["wheel"]);
	$speed = floatval($_POST["speed"]);

	// echo inputs
	echo "Data recieved: wheel: " .$wheel. ", distance: " .$dist. ", speed:" .$speed. ", datetime: " . date("Y-m-d H:i:s",$dt);

	// datebase connection details
	require ('../connect_db.php');

	// convert datetime from int from epoc to string for mySQL
	$hourDate = '\''.date("Y-m-d H:i:s",$dt).'\'';

	// variables to be calcualted by code at a later date
	// $run = 1;
	$laps = 1.000;

	$q = 'SELECT run FROM wheels WHERE wheel = '.$wheel;

	$r = mysqli_query($dbc,$q);

	if($r)
	{
		$result = mysqli_fetch_array($r, MYSQLI_ASSOC);
		$run = $result["run"];
	}
	else
	{
		echo 'db error:'.mysqli_error($dbc);
	}





	// insert readings into db
	$q = 'INSERT INTO readings (hourDate, wheel, distance, speed, run, laps) VALUES ('.$hourDate.','.$wheel.','.$dist.','.$speed.','.$run.','.$laps.')';

	$r = mysqli_query($dbc,$q);

	if($r)
	{
		echo ' -- new reading added to db';
	}
	else
	{
		echo 'db error:'.mysqli_error($dbc);
	}

	mysqli_close($dbc);

?>

