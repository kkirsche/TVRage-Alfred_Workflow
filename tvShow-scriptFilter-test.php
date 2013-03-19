<?php
     
     // Author: Kevin Kirsche
     // Version: 0.11

     date_default_timezone_set('America/New_York');

	require_once('workflows.php');
	require_once('TVRAGE/TVRAGE.class.php');
	require_once('TVRAGE/TV_Show.class.php');
	require_once('TVRAGE/TV_Shows.class.php');
	require_once('TVRAGE/TV_Episode.class.php');
	//create new workflow
	$w = new Workflows();
	// Grab input
	$input = "{query}";

	//Use the input to search TVRage
	$show = TV_Shows::search($input);
	//use foreach loop to set the information for each result
    foreach ($show as $each) {
    	//set the information available to us
     	$thisShow = array(
     		'uid' => $each->showId,
     		'arg' => $each->showLink,
     		'name' => $each->name,
     		'subtitle' => "",
     		'status' => $each->status,
     		'airDay' => $each->airDay,
     		'airTime' => $each->airTime,
     		'network' => $each->network,
     		'seasons' => $each->seasons,
     	);

          //check number of seasons to ensure we return the correct word
          if ($thisShow['seasons'] > 1) {
               $seasons = "seasons";
          } else {
               $seasons = "season";
          }

     	//set the subtitle based on the status of the show. We want the user to get information pertinent to the show.
     	if($thisShow['status'] == "Returning Series") {
     		$thisShow['subtitle'] = $each->name . " is a " . $thisShow['status'] . ". It is aired " . $thisShow['airDay'] . " at " . $thisShow['airTime'] . " on " . $thisShow['network'] . ".";
     	} else if ($thisShow['status'] == "Canceled/Ended") {
     		$thisShow['subtitle'] = $thisShow['name'] . " is " . $thisShow['status'] . ". It ran for " . $thisShow['seasons'] . " $seasons on " . $thisShow['network'] . ".";
     	} else if ($thisShow['status'] == "TBD/On The Bubble") {
     		$thisShow['subtitle'] = $thisShow['name'] . " is on the bubble for being renewed. If it is renewed, it airs on " . $thisShow['airDay'] . " at " . $thisShow['airTime'] . " on " . $thisShow['network'] . ".";
     	} else {
     		$thisShow['subtitle'] = $thisShow['name'] . " is aired on " . $thisShow['airDay'] . " at " . $thisShow['airTime'] . " on " . $thisShow['network'] . ".";
     	}

     	$fileIcon = "icon.png";
     	$valid = "yes";

     	//$w->result(uid, arg, title, subtitle, fileicon, valid, autocomplete)
		$w->result($thisShow['uid'], $thisShow['arg'], $thisShow['name'], $thisShow['subtitle'], $fileIcon, $valid, 'yes');
     }

     // Return the result xml
     echo $w->toxml();

?>