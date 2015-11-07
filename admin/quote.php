<?php require_once('../header_php.php'); ?>
<?php include('../header.php'); ?>













<?php


	if($action=="updatequote"){
		eval("\$sql=\"UPDATE quotes SET status='\$status_$quoteid' WHERE quoteid='$quoteid'\";");
		@mysql_query($sql);
	}





	








	$row=mysql_fetch_assoc(mysql_query("SELECT * FROM quotes WHERE quoteid='$quoteid'"));
	if(!$row){
		echo "ERROR: quote not found.";
		include('../footer.php');
		exit();
	}
	
	$fieldnames=array_keys($row);
	$vals=get_enum("quotes","status");
	
	echo "<p><a href=\"/admin/quote-print.php?quoteid=$quoteid\" target=\"_blank\">Printer friendly version</a></p>";
	echo "<form name='quotes' method='post' action='/admin/quote.php'>";
	echo "<table cellpadding='3' cellspacing='0' border='1'>";
	for($i=0;$i<count($fieldnames);$i++){
		echo "<tr valign='top'>";
		echo "<td>".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).":</td>";
		if($fieldnames[$i]=="status"){
			echo "<td><select name=\"status_".$row["quoteid"]."\" onchange=\"document.quotes.quoteid.value='".$row["quoteid"]."';document.quotes.submit();\">";
			for($j=0;$j<count($vals);$j++){
				if($vals[$j]!='incomplete'){
					echo "<option value=\"".$vals[$j]."\"";
					if($vals[$j]==$row[$fieldnames[$i]]){echo " selected=\"selected\"";}
					echo ">".$vals[$j]."</option>";
				}
			}
			echo "</select></td>";
		}
		elseif($fieldnames[$i]=="timestamp"){echo "<td>".date("m-d-y H:i",$row[$fieldnames[$i]])."</td>";}
		elseif($fieldnames[$i]=="quoteid"){echo "<td><a href='/admin/quote.php?quoteid=".$row[$fieldnames[$i]]."'>".$row[$fieldnames[$i]]."</td>";}
		/*
		elseif($fieldnames[$i]=="design_file"){
			if(!$row[$fieldnames[$i]]){echo "<td>&nbsp;</td>";}
			else{echo "<td><a href='/admin/designfiles/".$row[$fieldnames[$i]]."' target='_blank'>".$row[$fieldnames[$i]]."</a></td>";}
		}
		*/
		elseif(strlike($fieldnames[$i],'materials_supplied_by_')){
			if($row[$fieldnames[$i]]){echo "<td>yes</td>";}
			else{echo "<td>no</td>";}
		}
		else{echo "<td>".nl2br(formvalue($row[$fieldnames[$i]]))."&nbsp;</td>";}
		echo "</tr>";
	}
	
	// DESIGN FILES
	$sql="SELECT * FROM design_files WHERE quoteid='".$row["quoteid"]."'";
	$temp=mysql_unbuffered_query($sql);
	$files="";
	while($t=mysql_fetch_array($temp)){
		if($files){$files.="<br>";}
		$files.="<a href='/admin/getdoc.php?fileid=".$t["fileid"]."' target='_blank'>".$t["name"]."</a>";
		//$files.="<a href='/admin/designfiles/file_".$t["fileid"].$t["ext"]."' target='_blank'>".$t["name"]."</a>";
	}
	if($files){echo "<tr valign=\"top\"><td>Design Files: </td><td>$files</td></tr>";}
	else{echo "<tr><td>Design Files: </td><td>&nbsp;</td></tr>";}
	
	
	echo "</table>";
	echo "<input type='hidden' name='action' value='updatequote'/>";
	echo "<input type='hidden' name='quoteid' value=''/>";
?>
















<?php include('../footer.php'); ?>