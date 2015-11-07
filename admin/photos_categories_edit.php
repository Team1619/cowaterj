<?php require_once('../header_php.php'); ?>



<?php
if($action=="category_edit" && $categoryid){@mysql_query("UPDATE photocategories SET categoryname=\"$categoryname\", categorydescription=\"$categorydescription\" WHERE categoryid=\"$categoryid\"");}
$categoryrow=mysql_fetch_array(mysql_unbuffered_query("SELECT * FROM photocategories WHERE categoryid=\"$categoryid\""));
?>















<?php include('../header.php'); ?>










<form name='category_edit' method='post' action='/admin/photos_categories_edit.php'>
<table>
<tr><td>Category Name:</td><td><input type='text' name='categoryname' value="<?=formvalue($categoryrow["categoryname"])?>"/></td></tr>
<tr><td>Category Description:</td><td><textarea name='categorydescription' cols='20' rows='5'><?=$categoryrow["categorydescription"]?></textarea></td></tr>
<tr><td>&nbsp;</td><td><input type='submit' name='mysubmit' value='edit category'/></td></tr>
</table>
<input type='hidden' name='categoryid' value='<?=$categoryid?>'/>
<input type='hidden' name='action' value='category_edit'/>
</form>










<?php include('../footer.php'); ?>