<?php

class fileUpload{

	$allowedTypes;  	//This will be an array
	$maxSize;
	$uploadError;  		//This will be an array
	$uploadDirectory; 	//This will be path 
	$errorMessage;

	function setMaxSize($maxSize){
		$this->maxSize=$maxSize;
	}

	function setUploadDirectory($uploadDirectory){
		$this->uploadDirectory=$uploadDirectory;
	}
	
	function getFileUploadError($error){
		$uploadError = array( 
			UPLOAD_ERR_OK 			=> "There is no error.",
			UPLOAD_ERR_INI_SIZE		=> "The uploaded file exceeds the upload_max_filesize directive ",
			UPLOAD_ERR_FORM_SIZE	=> "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
			UPLOAD_ERR_PARTIAL		=> "The uploaded file was only partially uploaded.",
			UPLOAD_ERR_NO_FILE		=> "No file was uploaded.",
			UPLOAD_ERR_NO_TMP_DIR	=> "Missing a temporary folder.",
			UPLOAD_ERR_CANT_WRITE	=> "Failed to write file to disk.",
			UPLOAD_ERR_EXTENSION	=> "A PHP extension stopped the file upload."
		);
		return $uploadError[$error];
	}

	//This function traveres the allowedTypes array and compares each element to the $types parameter
	//If the parameter matches the value in the array, true is returned
	//Otherwise the function appends each allowed file type to an error message and then returns it
	function isAllowedType($type){
		if(in_array($type, $this->allowedTypes)){
			return true;
		}
		else{
			$this->errorMessage="<br>Only ";

			for($x=1; x <= count($this->allowedTypes); $x++){
				if(count($this->allowedTypes == 1)){
					$this->errorMessage.= $this->allowedTypes[x];
				}
				else {
					$this->errorMessage.= $this->allowedTypes[x];
					$this->errorMessage.=",";
				}
			}

			$this->errorMessage.=" file type(s) are allowed.";

		}
	}

	function setAllowedTypes($types){
		$this->allowedTypes=$types;
	}

}


