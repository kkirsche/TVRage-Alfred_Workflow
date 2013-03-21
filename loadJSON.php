<?php

$string = file_get_contents('config.json');

$decodedJSON = json_decode($string, true);
$decodedJSON['12hrtimezone'] = false;

$encodedJSON = json_encode($decodedJSON);

file_put_contents('config.json', $encodedJSON);

?>