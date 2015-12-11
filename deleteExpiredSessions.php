<?php

	$link = mysqli_connect("localhost", "avi", "avi","cl55-steel");

	$sessionToDelete = [];

	$currentTimeStampQuery = "SELECT * FROM `sessionInfo` WHERE `timeCreated` < (NOW() - INTERVAL 1 MINUTE)";
	$result =  mysqli_query($link, $currentTimeStampQuery);

	//echo $result;
	if(mysqli_num_rows($result) > 0){
	 while($row = mysqli_fetch_array($result)){   
	 	array_push($sessionToDelete, $row['sessionId']);
	 	echo $row['sessionId'];
	 }
	}
	else{
		echo "no rows found";
	}
	echo $sessionToDelete[0];
	// $row    =  mysqli_fetch_array($result);

	//   	 	while($row = mysqli_fetch_assoc($result)) {
	//    	 		echo ($row['sessionId']);   	
 //   	 	}
   	


	//echo time();
	//print_r($row);
	//$currentTimeStamp = $row['current_timestamp'];
	//echo $currentTimeStamp;

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