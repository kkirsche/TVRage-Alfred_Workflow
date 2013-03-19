 <?php
     // Author: Kevin Kirsche
     // Version: 0.05

     date_default_timezone_set('America/New_York');

	require_once('workflows.php');
	require_once('TVRAGE/TVRAGE.class.php');
	require_once('TVRAGE/TV_Show.class.php');
	require_once('TVRAGE/TV_Shows.class.php');
	require_once('TVRAGE/TV_Episode.class.php');
	//create new workflow
	$w = new Workflows();
	// Grab input
	$input = "01,02";

	

     // Return the result xml
     echo $w->toxml();

?>