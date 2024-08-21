<?php

  /*********************************************
   * Change this line to set the upload folder *
   *********************************************/
  $imageFolder = "uploads/";
  $ThumbSquareSize = "600";
  reset ($_FILES);
  $temp = current($_FILES);
  if (is_uploaded_file($temp['tmp_name'])){

	$ImageType	 	= $temp['type']; //get file type, returns "image/png", image/jpeg, text/plain etc.

	//Let's check allowed $ImageType, we use PHP SWITCH statement here
	switch(strtolower($ImageType))
	{
		case 'image/png':
			//Create a new image from file 
			$CreatedImage =  imagecreatefrompng($temp['tmp_name']);
			break;
		case 'image/gif':
			$CreatedImage =  imagecreatefromgif($temp['tmp_name']);
			break;
		case 'image/webp':
			$CreatedImage =  imagecreatefromwebp($temp['tmp_name']);
			break;					
		case 'image/jpeg':
		case 'image/pjpeg':
			$CreatedImage = imagecreatefromjpeg($temp['tmp_name']);
			break;
		default:
			die('Unsupported File!'); //output error and exit
	}	
	
    /*
      If your script needs to receive cookies, set images_upload_credentials : true in
      the configuration and enable the following two headers.
    */
    // header('Access-Control-Allow-Credentials: true');
    // header('P3P: CP="There is no P3P policy."');

    // Sanitize input
    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
        header("HTTP/1.0 500 Invalid file name.");
        return;
    }

    // Verify extension
    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png", "webp"))) {
        header("HTTP/1.0 500 Invalid extension.");
        return;
    }
	$year_folder = $imageFolder . date("Y");
	$month_folder = $year_folder . '/' . date("m");

	!file_exists($year_folder) && mkdir($year_folder , 0777);
	!file_exists($month_folder) && mkdir($month_folder, 0777);
    // Accept upload if there was no origin, or if it is an accepted origin
    $filetowrite = $month_folder . '/' . $temp['name'];
	list($CurWidth,$CurHeight)=getimagesize($temp['tmp_name']);
	if(resizeImage($CurWidth,$CurHeight,$ThumbSquareSize,$filetowrite,$CreatedImage,$ImageType))
	{	

    // Respond to the successful upload with JSON.
    // Use a location key to specify the path to the saved image resource.
    // { location : '/your/uploaded/image/file'}
    echo json_encode(array('location' => $filetowrite));
	}
  } else {
    // Notify editor that the upload failed
    header("HTTP/1.0 500 Server Error");
  }
  
// This function will proportionally resize image 
function resizeImage($CurWidth,$CurHeight,$MaxSize,$DestFolder,$SrcImage,$ImageType)
{
	
	
	//Check Image size is not 0
	if($CurWidth <= 0 || $CurHeight <= 0) 
	{
		return false;
	}
	$Quality = '90';
	//Construct a proportional size of new image
	$ImageScale      	= min($MaxSize/$CurWidth, $MaxSize/$CurWidth); 
	if ($CurWidth <= $MaxSize) {
		$NewWidth  			= $CurWidth;
		$NewHeight 			= $CurHeight;
	} else {
	$NewWidth  			= ceil($ImageScale*$CurWidth);
	$NewHeight 			= ceil($ImageScale*$CurHeight);
	}
	$NewCanves 			= imagecreatetruecolor($NewWidth, $NewHeight);
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

			
		//Or Save image to the folder
		imagejpeg($NewCanves,$DestFolder,$Quality);
			
	//Destroy image, frees memory	
	if(is_resource($NewCanves)) {imagedestroy($NewCanves);} 
	return true;
	}

}
  
?>