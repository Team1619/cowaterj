<?php require_once('../header_php.php'); ?>






<?php
if($action=="photo_edit" && $photoid){
	@mysql_query("UPDATE photos SET caption=\"$photo_caption\" WHERE photoid=\"$photoid\"");
	if(is_uploaded_file($_FILES['photo_name']['tmp_name'])){
		@move_uploaded_file($_FILES['photo_name']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/gallery/gallery_$photoid.jpg");
	}
	@mysql_query("DELETE FROM photos_categories WHERE photoid='$photoid'");
	if($category1){@mysql_query("INSERT INTO photos_categories (photoid,categoryid) VALUES ('$photoid','$category1')");}
	if($category2 && $category2!=$category1){@mysql_query("INSERT INTO photos_categories (photoid,categoryid) VALUES ('$photoid','$category2')");}
	if($category3 && $category3!=$category2 && $category3!=$category1){@mysql_query("INSERT INTO photos_categories (photoid,categoryid) VALUES ('$photoid','$category3')");}
}
$photorow=mysql_fetch_array(mysql_unbuffered_query("SELECT * FROM photos WHERE photoid='$photoid'"));
?>










<?php include('../header.php'); ?>



Photo specs:  Photos should be either a .jpg or .gif type.  Photos should be a minimum of 450 pixels in their smallest dimension.  There is no upper bound for photo size (besides 5 megabytes), but the smaller they are the faster they will load.<br><br>





<!--<form name='photo_edit' method='post' action='/admin/photos_edit.php' enctype='multipart/form-data'>-->
<form name='photo_edit' method='post' action='/admin/photos.php' enctype='multipart/form-data'>
<table>
<tr><td colspan='2'><?php echo "<a href=\"/gallery/gallery_".$row["photoid"].".jpg\" target=\"_blank\"><img src=\"/images/getimage.php?path=".$_SERVER['DOCUMENT_ROOT']."/gallery/gallery_$photoid.jpg&amp;maxsize=300\"/></a>"; ?></td></tr>
<tr><td>Replacement Photo (.jpg or .gif):</td><td><input type='file' name='photo_name'/></td></tr>
<tr><td>Caption:</td><td><textarea name='photo_caption' cols='20' rows='5'><?=$photorow["caption"]?></textarea></td></tr>
<?php
	$result=mysql_unbuffered_query("SELECT photocategories.categoryid, photocategories.categoryname FROM photocategories, photos_categories WHERE photocategories.categoryid=photos_categories.categoryid AND photos_categories.photoid='$photoid' ORDER BY photocategories.categoryname");
	$categories=array();
	$i=0;
	while($row=mysql_fetch_array($result)){
		$categories[$i]["categoryid"]=$row["categoryid"];
		$categories[$i]["categoryname"]=$row["categoryname"];
		$i++;
	}
	echo "<tr><td>Category #1:</td><td><select name='category1'><option value='0'></option>";
	$result=mysql_unbuffered_query("SELECT categoryid, categoryname FROM photocategories ORDER BY categoryname");
	while($row=mysql_fetch_array($result)){
		echo "<option value='".$row["categoryid"]."'";
		if($categories[0]["categoryid"]==$row["categoryid"]){echo " selected='selected'";}
		echo ">".$row["categoryname"]."</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><td>Category #2:</td><td><select name='category2'><option value='0'></option>";
	$result=mysql_unbuffered_query("SELECT categoryid, categoryname FROM photocategories ORDER BY categoryname");
	while($row=mysql_fetch_array($result)){
		echo "<option value='".$row["categoryid"]."'";
		if($categories[1]["categoryid"]==$row["categoryid"]){echo " selected='selected'";}
		echo ">".$row["categoryname"]."</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><td>Category #3:</td><td><select name='category3'><option value='0'></option>";
	$result=mysql_unbuffered_query("SELECT categoryid, categoryname FROM photocategories ORDER BY categoryname");
	while($row=mysql_fetch_array($result)){
		echo "<option value='".$row["categoryid"]."'";
		if($categories[2]["categoryid"]==$row["categoryid"]){echo " selected='selected'";}
		echo ">".$row["categoryname"]."</option>";
	}
	echo "</select></td></tr>";
?>
<tr><td>&nbsp;</td><td><input type='submit' name='mysubmit' value='edit photo'/></td></tr>
</table>
<input type='hidden' name='action' value='photo_edit'/>
<input type='hidden' name='photoid' value='<?=$photoid?>'/>
<input type='hidden' name='MAX_FILE_SIZE' value='500000'>
</form>












<?php include('../footer.php'); ?>