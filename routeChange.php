<?php

$wheel = intval($_GET["wheel"]);
$route = intval($_GET["route"]);

// database login
require ('../connect_db.php');

// update distance and show db again

$q = 'UPDATE wheels SET run = '.$route.' WHERE wheel = "'.$wheel.'";';

$r = mysqli_query($dbc,$q);

if($r)
{
	echo '<p>Route updated.</p>';
}
else
{
	echo '<p>'.mysqli_error($dbc).'</p>';
}

?>


