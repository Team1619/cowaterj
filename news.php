<?php require_once('header_php.php'); ?>







<?php
if($action=='logout'){
	@mysql_query("DELETE FROM visitors_accounts WHERE visitorid='$visitorid'");
	$accountrow="";
}
?>





<?php include('header.php'); ?>


<p><a href="/CWJ_2009_A2Z.pdf" target="_blank">Read the latest news about Colorado WaterJet</a></p>






<?php include('footer.php'); ?>