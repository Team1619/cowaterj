<?php require_once('../header_php.php'); ?>
<?php include('../header.php'); ?>













<?php
	$row=mysql_fetch_assoc(mysql_unbuffered_query("SELECT * FROM accounts ORDER BY last_name, first_name"));
	if(!$row){
		echo "ERROR: account not found.";
		include('../footer.php');
		exit();
	}
	
	$fieldnames=array_keys($row);
	echo "<table>";
	for($i=0;$i<count($fieldnames);$i++){
		echo "<tr valign='top'>";
		echo "<td>".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).":</td>";
		echo "<td>".nl2br($row[$fieldnames[$i]])."</td>";
		echo "</tr>";
	}
	echo "</table>";
?>
















<?php include('../footer.php'); ?>