<?php

	$link = mysqli_connect("localhost", "avi", "avi","cl55-steel");

	$currentTimeStampQuery = "SELECT CURRENT_TIMESTAMP";
	$result =  mysqli_query($link, $currentTimeStampQuery);
	$row    =  mysqli_fetch_array($result);


	$currentTimeStamp = $row['current_timestamp'];

	echo $current_timestamp;

	// $query  = "SELECT * FROM `sessionInfo`";
	// $result = mysqli_query($link, $query);
	// $row    = mysqli_fetch_array($result);

	// $sessionId	 = array();
	// $timeCreated = array();



	// if (mysqli_num_rows($result) > 0) {
 //    // output data of each row
 //   	 	while($row = mysqli_fetch_assoc($result)) {
 //   	 		$sessionId.push($row['sessionId']);
 //   	 		$timeCreated.push($row['timeCreated']);
 //   	 	}
 //   	}

 //   	echo $sessionId[0] . " ".$timeCreated[0];
	
	




?>