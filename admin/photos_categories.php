<?php require_once('../header_php.php'); ?>



<?php
if($action=="category_new" && $categoryname){@mysql_query("INSERT INTO photocategories (categoryname,categorydescription) VALUES (\"$categoryname\",\"$categorydescription\")");}
elseif($action=="category_delete" && $categoryid){
	// BEFORE DELETING CHECK TO SEE IF THERE ARE ANY IMAGES IN THIS CATEGORY
	$row=mysql_fetch_array(mysql_unbuffered_query("SELECT count(photoid) AS 'num' FROM photos_categories WHERE categoryid='$categoryid'"));
	if($row["num"]){$error="You tried to delete a category that contains some photos.  Please remove the photos from this category before trying to delete it.";}
	else{@mysql_query("DELETE FROM photocategories WHERE categoryid='$categoryid'");}
}
?>















<?php include('../header.php'); ?>






<?php if($error){echo "<div class=\"error\">ERROR: $error</div><br>";} ?>



<form name='category_new' method='post' action='/admin/photos_categories.php'>
<table>
<tr><td>Category Name:</td><td><input type='text' name='categoryname'/></td></tr>
<tr><td>Category Description:</td><td><textarea name='categorydescription' cols='20' rows='5'></textarea></td></tr>
<tr><td>&nbsp;</td><td><input type='submit' name='mysubmit' value='add category'/></td></tr>
</table>
<input type='hidden' name='action' value='category_new'/>
</form>




<br><br>






<table>

<?php
$result=mysql_unbuffered_query("SELECT * FROM photocategories");
while($row=mysql_fetch_array($result)){
	echo "<tr>";
	echo "<td>".$row["categoryname"]."</td>";
	echo "<td>".$row["categorydescription"]."</td>";
	echo "<td><a href=\"/admin/photos_categories.php?action=category_delete&categoryid=".$row["categoryid"]."\" onclick=\"return confirm('Are you sure you want to delete this category?');\">delete</a></td>";
	echo "<td><a href=\"/admin/photos_categories_edit.php?categoryid=".$row["categoryid"]."\">edit</a></td>";
	echo "</tr>";
}
?>
</table>














<?php include('../footer.php'); ?>