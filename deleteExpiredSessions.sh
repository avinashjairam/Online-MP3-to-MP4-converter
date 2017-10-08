#!/usr/bin/php


<?php

	//Database connection link	
	$link = mysqli_connect("localhost", "", "","");

	//printf(mysqli_connect_error());
	//Array to hold the ids of the expired sessions
	$sessionToDelete = [];

	//SQl Query 
	$currentTimeStampQuery = "SELECT * FROM `sessionInfo` WHERE `timeCreated` < (NOW() - INTERVAL 1 MINUTE)";
	$result =  mysqli_query($link, $currentTimeStampQuery);


	if(mysqli_num_rows($result) > 0){
	 while($row = mysqli_fetch_array($result)){   
	 	$sessionId = $row['sessionId'];

	 	echo $sessionId . "<br>";
	 	array_push($sessionToDelete, $row['sessionId']);
	 	exec("rm -rf $sessionId  2>&1",$output,$return );

	 	echo "return is ".$return;

	 	echo "output is ";
	 	print_r($output);

	 	if($return==0){

		 	$deleteExpiredSessionsQuery = "DELETE FROM `sessionInfo` WHERE `timeCreated` < (NOW() - INTERVAL 1 MINUTE)";	
			$result = mysqli_query($link, $deleteExpiredSessionsQuery);
		}
	

	 }
	}
	else{
		echo "no rows found";
	}
	


	//echo $sessionToDelete[0];

?>
