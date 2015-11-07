<?php require_once('../header_php.php'); ?>



<?php
if($action=="photo_new" && is_uploaded_file($_FILES['photo_name']['tmp_name'])){
	@mysql_query("INSERT INTO photos (caption) VALUES (\"$photo_caption\")");
	$photoid=mysql_insert_id();
	@move_uploaded_file($_FILES['photo_name']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/gallery/gallery_$photoid.jpg");
	mkthumb("gallery_$photoid.jpg",200);
	if($category1){@mysql_query("INSERT INTO photos_categories (photoid,categoryid) VALUES ('$photoid','$category1')");}
	if($category2 && $category2!=$category1){@mysql_query("INSERT INTO photos_categories (photoid,categoryid) VALUES ('$photoid','$category2')");}
	if($category3 && $category3!=$category2 && $category3!=$category1){@mysql_query("INSERT INTO photos_categories (photoid,categoryid) VALUES ('$photoid','$category3')");}
}
elseif($action=="photo_delete" && $photoid){
	@mysql_query("DELETE FROM photos WHERE photoid='$photoid'");
	@mysql_query("DELETE FROM photos_categories WHERE photoid='$photoid'");
	@unlink($_SERVER['DOCUMENT_ROOT']."/gallery/gallery_$photoid.jpg");
	@unlink($_SERVER['DOCUMENT_ROOT']."/gallery/gallery_$photoid_th.jpg");
}
?>















<?php include('../header.php'); ?>










<form name='photo_new' method='post' action='/admin/photos.php' enctype='multipart/form-data'>
<table>
<tr><td>Photo:</td><td><input type='file' name='photo_name'/></td></tr>
<tr><td>Caption:</td><td><textarea name='photo_caption' cols='20' rows='5'></textarea></td></tr>
<tr><td>Category #1:</td><td><select name='category1'><option value='0'></option>
<?php
$result=mysql_unbuffered_query("SELECT categoryid,categoryname FROM photocategories ORDER BY categoryname");
while($row=mysql_fetch_array($result)){echo "<option value='".$row["categoryid"]."'>".$row["categoryname"]."</option>";}
?>
</select></td></tr>
<tr><td>Category #2:</td><td><select name='category2'><option value='0'></option>
<?php
$result=mysql_unbuffered_query("SELECT categoryid,categoryname FROM photocategories ORDER BY categoryname");
while($row=mysql_fetch_array($result)){echo "<option value='".$row["categoryid"]."'>".$row["categoryname"]."</option>";}
?>
</select></td></tr>
<tr><td>Category #3:</td><td><select name='category3'><option value='0'></option>
<?php
$result=mysql_unbuffered_query("SELECT categoryid,categoryname FROM photocategories ORDER BY categoryname");
while($row=mysql_fetch_array($result)){echo "<option value='".$row["categoryid"]."'>".$row["categoryname"]."</option>";}
?>
</select></td></tr>
<tr><td>&nbsp;</td><td><input type='submit' name='mysubmit' value='add photo'/></td></tr>
</table>
<input type='hidden' name='action' value='photo_new'/>
<input type='hidden' name='MAX_FILE_SIZE' value='500000'>
</form>




<br><br>






<table>

<?php
$result=mysql_query("SELECT * FROM photos");
while($row=mysql_fetch_array($result)){
	echo "<tr>";
	echo "<td><img src=\"/gallery/gallery_".$row["photoid"]."_th.jpg\"/></td>";
	echo "<td>".$row["caption"]."</td>";
	echo "<td>";
	$temp_result=mysql_unbuffered_query("SELECT photocategories.categoryname FROM photocategories, photos_categories WHERE photocategories.categoryid=photos_categories.categoryid AND photos_categories.photoid='".$row["photoid"]."'");
	$msg="";
	while($temp_row=mysql_fetch_array($temp_result)){
		if($msg!=""){$msg.="<br>";}
		$msg.=$temp_row["categoryname"];
	}
	echo "$msg</td>";
	echo "<td><a href=\"/admin/photos.php?action=photo_delete&photoid=".$row["photoid"]."\" onclick=\"return confirm('Are you sure you want to delete this photo?');\">delete</a></td>";
	echo "<td><a href=\"/admin/photos_edit.php?photoid=".$row["photoid"]."\">edit</a></td>";
	echo "</tr>";
}
?>
</table>














<?php include('../footer.php'); ?>