<?php require_once('../header_php.php'); ?>




<?php include('../header.php'); ?>


<?php
	$row=mysql_fetch_array(mysql_unbuffered_query("SELECT quoteid FROM quotes WHERE quoteid='$quoteid' AND accountid='".$accountrow["accountid"]."' AND status!='incomplete'"));
	if(!$row){
		echo "We're sorry - this receipt could not be displayed.  If you feel you have received this message in error please <a href='/contact.php'>contact us</a>.";
		include('../footer.php');
		exit();
	}
	echo receipt($quoteid);
?>





<?php include('../footer.php'); ?>