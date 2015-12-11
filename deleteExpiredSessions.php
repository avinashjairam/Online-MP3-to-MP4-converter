<?php

	//Database connection link
	

	//Array to hold the ids of the expired sessions
	$sessionToDelete = [];

	//SQl Query 
	$currentTimeStampQuery = "SELECT * FROM `sessionInfo` WHERE `timeCreated` < (NOW() - INTERVAL 3 MINUTE)";
	$result =  mysqli_query($link, $currentTimeStampQuery);

	$sessionId;

	//echo $result;
	if(mysqli_num_rows($result) > 0){
	 while($row = mysqli_fetch_array($result)){   
	 	$sessionId = $row['sessionId'];
	 	array_push($sessionToDelete, $row['sessionId']);
	 	exec("rm -rf $sessionId");
	 }
	}
	else{
		echo "no rows found";
	}

	echo $sessionToDelete[0];

	$deleteExpiredSessionsQuery = "DELETE FROM `sessionInfo` WHERE `timeCreated` < (NOW() - INTERVAL 1 MINUTE)";	
	$result = mysqli_query($link, $deleteExpiredSessionsQuery);
?>