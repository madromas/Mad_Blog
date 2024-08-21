<?php

require 'king-base.php';
/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: LICENCE.html
*/
if(isset($_FILES["myfile"]))
{
	

	############ Edit settings ##############
	$ThumbSquareSize 		= 600; //Thumbnail will be 200x200
	$ThumbPrefix			= "thumb_"; //Normal thumb Prefix
	$DestinationDirectory	= 'uploads/'; //specify upload directory ends with / (slash)
	$Quality 				= 95; //jpeg quality
	##########################################
	
	//check if this is an ajax request
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		die();
	}
	
	// check $_FILES['myfile'] not empty
	if(!isset($_FILES['myfile']) || !is_uploaded_file($_FILES['myfile']['tmp_name']))
	{
			die('Something wrong with uploaded file, something missing!'); // output error when above checks fail.
	}
	
	// Random number will be added after image name
	$RandomNumber 	= rand(0, 999999); 

	$ImageName 		= str_replace(' ','-',strtolower($_FILES['myfile']['name'])); //get image name
	$ImageSize 		= $_FILES['myfile']['size']; // get original image size
	$TempSrc	 	= $_FILES['myfile']['tmp_name']; // Temp name of image file stored in PHP tmp folder
	$ImageType	 	= $_FILES['myfile']['type']; //get file type, returns "image/png", image/jpeg, text/plain etc.

	//Let's check allowed $ImageType, we use PHP SWITCH statement here
	switch(strtolower($ImageType))
	{
		case 'image/png':
			//Create a new image from file 
			$CreatedImage =  imagecreatefrompng($_FILES['myfile']['tmp_name']);
			break;
		case 'image/webp': 
			$CreatedImage =  imagecreatefromwebp($_FILES['myfile']['tmp_name']);
			break;	
		case 'image/gif':
			$CreatedImage =  imagecreatefromgif($_FILES['myfile']['tmp_name']);
			break;			
		case 'image/jpeg':
		case 'image/pjpeg':
			$CreatedImage = imagecreatefromjpeg($_FILES['myfile']['tmp_name']);
			break;
		default:
			die('Unsupported File!'); //output error and exit
	}
	
	
	$file_parts = pathinfo($ImageName);

	switch($file_parts['extension'])
	{
		case "jpg":
			break;
		case "jpeg":
			break;
		case "png":
			break;
		case "webp":
			break;	
		case "gif":
			break;
		default:
			die('Unsupported File Extension!'); //output error and exit
	}
	
	//PHP getimagesize() function returns height/width from image file stored in PHP tmp folder.
	//Get first two values from image, width and height. 
	//list assign svalues to $CurWidth,$CurHeight
	list($CurWidth,$CurHeight)=getimagesize($TempSrc);
	
	//Get file extension from Image name, this will be added after random name
	$ImageExt = substr($ImageName, strrpos($ImageName, '.'));
  	$ImageExt = str_replace('.','',$ImageExt);
	
	//remove extension from filename
	$ImageName 		= preg_replace("/\\.[^.\\s]{3,4}$/", "", $ImageName); 
	
	//Construct a new name with random number and extension.
	$NewImageName = $ImageName.'-'.$RandomNumber.'.'.$ImageExt;
	//set the Destination Image
	$thumb_DestRandImageName 	= $DestinationDirectory.$ThumbPrefix.$NewImageName; //Thumbnail name with destination directory
	$DestRandImageName 			= $DestinationDirectory.$NewImageName; // Image with destination directory
	$year_folder = $DestinationDirectory . date("Y");
	$month_folder = $year_folder . '/' . date("m");

	!file_exists($year_folder) && mkdir($year_folder , 0777);
	!file_exists($month_folder) && mkdir($month_folder, 0777);
	$path = $month_folder . '/' . $NewImageName;
	$path2 = $month_folder . '/' .$ThumbPrefix.$NewImageName;	
	
	$ret = array();
	//Resize image to Specified Size by calling resizeImage function.
	if(resizeImage($CurWidth,$CurHeight,$ThumbSquareSize,$path2,$CreatedImage,$Quality,$ImageType))
	{
		//Create a square Thumbnail right after, this time we are using cropImage() function
		
	if(!is_array($_FILES["myfile"]["tmp_name"])) //single file
	{

 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$path);
    	$ret[]= $NewImageName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["tmp_name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$NewImageName = $_FILES["myfile"]["tmp_name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$path);
	  	$ret[]= $NewImageName;
	  }
	
	}
    echo json_encode($ret);
		/*
		// Insert info into database table!
		mysql_query("INSERT INTO myImageTable (ImageName, ThumbName, ImgPath)
		VALUES ($DestRandImageName, $thumb_DestRandImageName, 'uploads/')");
		*/

	}else{
		die('Resize Error'); //output error
	}
}


// This function will proportionally resize image 
function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$Quality,$ImageType)
{
	//Check Image size is not 0
	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}
	
	//Construct a proportional size of new image
	$ImageScale      	= min($MaxSize/$CurWidth, $MaxSize/$CurWidth); 
	$NewWidth  			= ceil($ImageScale*$CurWidth);
	$NewHeight 			= ceil($ImageScale*$CurHeight);
	$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
	$watermark_png_file = 'watermark/watermark.png';
	// Resize Image
	if(imagecopyresampled($NewCanves, $SrcImage,0, 0, 0, 0, $NewWidth, $NewHeight, $CurWidth, $CurHeight))
	{
		switch(strtolower($ImageType))
		{
			case 'image/png':
				imagepng($NewCanves,$DestFolder);
				break;
			case 'image/gif':
				imagegif($NewCanves,$DestFolder);
				break;	
			case 'image/webp':
				imagewebp($NewCanves,$DestFolder);
				break;				
			case 'image/jpeg':
			case 'image/pjpeg':
				imagejpeg($NewCanves,$DestFolder,$Quality);
				break;
			default:
				return false;
		}
		if (!qa_opt('disable_watermark')) {
		$watermark = imagecreatefrompng($watermark_png_file); //watermark image

		$watermark_width  = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		
		$watermark_left = ($NewWidth)-($watermark_width); //watermark left
		$watermark_bottom = ($NewHeight)-($watermark_height); //watermark bottom	
		
		imagecopy($NewCanves, $watermark, $watermark_left, $watermark_bottom, 0, 0, $watermark_width, $watermark_height); //merge image
		}
		//Or Save image to the folder
		imagejpeg($NewCanves,$DestFolder,$Quality);
			
	//Destroy image, frees memory	
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
	return true;
	}

}