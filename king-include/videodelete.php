<?php
$output_dir = "videos/";
if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
{
	$fileName =$_POST['name'];
	$fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files	
	$filePath = $output_dir. $fileName. ".jpg";
	$filePath2 = $output_dir. $fileName. ".mp4";
	$filePath3 = $output_dir. $fileName. ".webm";
	$output_realdir = realpath($filePath2);
	$output_realdir = realpath($filePath3);
if ($output_realdir === false) {
    //Directory Traversal!
} else {
	if (file_exists($filePath)) 
	{
        unlink($filePath);
    }
	
	if (file_exists($filePath2)) 
	{
        unlink($filePath2);
    }
}		

}
?>