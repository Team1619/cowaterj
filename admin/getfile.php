<?php
	include("../header_php.php");
	$fileid=$_GET['fileid'];
	//////////////////////////////////////////////////////////////////////////////
	// FIND DOCUMENT
	$row=mysql_fetch_array(mysql_unbuffered_query("SELECT * FROM design_files WHERE fileid=\"$fileid=\" LIMIT 1"));
	
	//////////////////////////////////////////////////////////////////////////////
	// EXIT IF WE CAN'T FIND DOCUMENT
	if(!$row["fileid"]){
		echo 'ERROR - FILE NOT FOUND';
		exit();
	}
	
	//////////////////////////////////////////////////////////////////////////////
	// GET FILE INFO
	$path=$_SERVER['DOCUMENT_ROOT'].'/file_$fileid'.$row["ext"];
	if($row["type"]){header('Content-Type: '.$row["type"]);}
	header("Content-Length: ".filesize($path)); 
	header("Content-Disposition:  inline; filename=\"".$row["name"]."\""); 
	readfile($path);
?>