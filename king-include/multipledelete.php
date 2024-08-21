<?php
$output_dir = "uploads/";
$ThumbPrefix = "thumb_";
$month_folder = date("Y") . '/' . date("m") . '/';
$output_realdir = realpath($output_dir.$month_folder);
if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
{
	$fileName = $_POST['name'];
	$thumb_DestRandImageName 	= $output_dir.$month_folder.$ThumbPrefix.$fileName; //Thumbnail name with destination directory

	$fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files	
	$filePath = $output_dir.$month_folder.$fileName;
if ($output_realdir === false) {
    //Directory Traversal!
} else {
	if (file_exists($filePath)) 
	{
        unlink($filePath);
    }
	if (file_exists($thumb_DestRandImageName)) 
	{
        unlink($thumb_DestRandImageName);
    }
}	

}

?>