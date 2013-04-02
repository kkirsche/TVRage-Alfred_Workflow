 <?php
     // Author: Kevin Kirsche
     // Version: 0.06

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

	//next we want to separate the show name from the season and from the episode
	preg_match("/(.+)S(\d{2})E(\d{2})/", $input, $showInfoArray);

	if(empty($showInfoArray)) {
		$fileIcon = "icon_waiting.png";
     		$valid = "no";
		$w->result("52847495564616816814684357", "http://www.tvrage.com/", "Preparing to Search...", "Waiting for the complete input. Please enter \"[SHOW NAME] S##E##\" to begin the search.", $fileIcon, $valid, 'yes');
		// Return the result xml
     echo $w->toxml();
	} else {
		$showName = $showInfoArray['1'];
		$showSeason = $showInfoArray['2'];
		$showEpisode = $showInfoArray['3'];

		$tvShowSearch = TV_Shows::search($showName);
		foreach ($tvShowSearch as $returnedTVShow) {
			//First, we want to get the TV show information.
			$thisShowInfo = array(
				'showId' => $returnedTVShow->showId,
				'showName' => $returnedTVShow->name
			);
			//Now that we have the current TV Show ID #, we can use this to search for the specific episode
			$useIDToGetToEpisode = TV_Shows::findById($thisShowInfo['showId']);
			$episode = $useIDToGetToEpisode->getEpisode($showSeason, $showEpisode);

			//Take the information we got from TV Rage, and put it in an array
			$episodeInformation = array(
				'uid' => $thisShowInfo['showId'],
		     		'arg' => $episode->url,
		     		'name' => $episode->title . ", " . $thisShowInfo['showName'] . " aired on " . $episode->formattedAirDate . ".",
		     		'subtitle' => 'Episode ' . $showEpisode . " of " . $thisShowInfo['showName'] . " was named " . $episode->title . " and aired on " . $episode->formattedAirDate . ".",
		     		'fileIcon' => 'icon.png',
		     		'valid' => 'yes'
			);

			//prep it for return to Alfred!
			$w->result($episodeInformation['uid'], $episodeInformation['arg'], $episodeInformation['name'], $episodeInformation['subtitle'], $episodeInformation['fileIcon'], $episodeInformation['valid'], 'yes');
		//end foreach loop
		}
	//end else
		// Return the result xml
     	echo $w->toxml();
	}



?>