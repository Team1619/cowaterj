<?php require_once('../header_php.php'); ?>



<?php include('../header.php'); ?>

&nbsp;







<?php
	$result=mysql_query("SELECT * FROM junk");
	while($row=mysql_fetch_array($result)){
		$mycategory=7;
		if($row["category"]=='machine'){$mycategory=8;}
		elseif($row["category"]=='arch'){$mycategory=9;}
		elseif($row["category"]=='sign'){$mycategory=10;}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// INSERT INTO PHOTOS
		@mysql_query("INSERT INTO photos (caption) VALUE (\"".$row["caption"]."\")");
		$photoid=mysql_insert_id();
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// INSERT INTO PHOTOS_CATEGORIES
		@mysql_query("INSERT INTO photos_categories (photoid,categoryid) VALUES ('$photoid','$mycategory')");
				
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// MOVE AND RENAME FILES
		$filename=$_SERVER['DOCUMENT_ROOT']."/stuff/".$row["name"];
		list($width,$height)=getimagesize($filename);
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($filename);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);
		imagejpeg($image_p,$_SERVER['DOCUMENT_ROOT']."/gallery/gallery_$photoid.jpg",100);
		imagedestroy($image_p);
		unlink($filename);
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// CREATE THUMBNAIL
		$filename=$_SERVER['DOCUMENT_ROOT']."/gallery/gallery_$photoid.jpg";
		$filename_new=substr($filename,0,-4)."_th.jpg";
		$maxdimension=200;

		$width=$maxdimension;
		$height=$maxdimension;
				
		// Get new dimensions
		list($width_orig, $height_orig) = getimagesize($filename);
		
		$ratio_orig = $width_orig/$height_orig;
		
		if($width/$height>$ratio_orig){$width = $height*$ratio_orig;}
		else{$height = $width/$ratio_orig;}
		
		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($filename);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		
		// Output
		imagejpeg($image_p,$filename_new,100);
		imagedestroy($image_p);
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// DELETE FROM JUNK
		@mysql_query("DELETE FROM junk WHERE id='".$row["id"]."'");
	}
?>





























<?php include('../footer.php'); ?>