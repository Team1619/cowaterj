<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/header_php.php");
	
	//////////////////////////////////////////////////////////////////////////////
	// FIND DOCUMENT
	$row=mysql_fetch_array(mysql_unbuffered_query("SELECT * FROM design_files WHERE  fileid=\"$fileid=\" LIMIT 1"));
	
	//////////////////////////////////////////////////////////////////////////////
	// EXIT IF WE CAN'T FIND DOCUMENT
	if(!$row["fileid"]){exit();}
	
	//////////////////////////////////////////////////////////////////////////////
	// GET FILE INFO
	$path=$_SERVER['DOCUMENT_ROOT']."/admin/designfiles/file_$fileid".$row["ext"];
	header("Content-Type: ".$row["type"]);
	header("Content-Length: ".filesize($path)); 
	header("Content-Disposition:  inline; filename=\"".$row["name"]."\""); 
	readfile($path);
?>