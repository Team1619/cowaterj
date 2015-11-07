<?php require_once('../header_php.php'); ?>
<?php include('../header.php'); ?>














<?php
/*
// DELETE DESIGN FILES FROM EVERYTHING OLDER THAN OCT 2008
$time=strtotime("1 October 2008");
$sql="SELECT design_files.name, design_files.fileid, design_files.ext FROM design_files, quotes WHERE design_files.quoteid=quotes.quoteid AND quotes.timestamp<'".$time."'";
$rs=mysql_query($sql);
while($row=mysql_fetch_array($rs)){
	$file=$_SERVER["DOCUMENT_ROOT"]."/admin/designfiles/".$row["name"];
	if(!file_exists($file)){$file=$_SERVER["DOCUMENT_ROOT"]."/admin/designfiles/file_".$row["fileid"].$row["ext"];}
	chmod($file,0755);
	if(unlink($file)){
		@mysql_unbuffered_query("DELETE FROM design_files WHERE fileid='".$row["fileid"]."'");
	}
}
*/



if($quoteid && $action=="updatequote"){
	eval("\$sql=\"UPDATE quotes SET status='\$status_$quoteid' WHERE quoteid='$quoteid'\";");
	@mysql_query($sql);
}
if($_GET["quoteid"] && $_GET["action"]=="delete_design_files"){
	$rs=mysql_query("SELECT name, fileid, ext FROM design_files WHERE quoteid='".$_GET["quoteid"]."'");
	while($row=mysql_fetch_array($rs)){
		$file=$_SERVER["DOCUMENT_ROOT"]."/admin/designfiles/".$row["name"];
		if(!file_exists($file)){$file=$_SERVER["DOCUMENT_ROOT"]."/admin/designfiles/file_".$row["fileid"].$row["ext"];}
		chmod($file,0755);
		if(unlink($file)){
			@mysql_unbuffered_query("DELETE FROM design_files WHERE fileid='".$row["fileid"]."'");
		}
	}
	echo "<p><strong>The design files for quote #".$_GET["quoteid"]." were deleted.</p>";
}
if($_GET["quoteid"] && $_GET["action"]=="delete_quote"){
	// FIRST DELETE DESIGN FILES
	$rs=mysql_query("SELECT name, fileid, ext FROM design_files WHERE quoteid='".$_GET["quoteid"]."'");
	while($row=mysql_fetch_array($rs)){
		$file=$_SERVER["DOCUMENT_ROOT"]."/admin/designfiles/".$row["name"];
		if(!file_exists($file)){$file=$_SERVER["DOCUMENT_ROOT"]."/admin/designfiles/file_".$row["fileid"].$row["ext"];}
		chmod($file,0755);
		if(unlink($file)){
			@mysql_unbuffered_query("DELETE FROM design_files WHERE fileid='".$row["fileid"]."'");
		}
	}
	@mysql_unbuffered_query("DELETE FROM quotes WHERE quoteid='".$_GET["quoteid"]."'");
	echo "<p><strong>Quote #".$_GET["quoteid"]." was deleted.</p>";
}



$count=0;
$vals=get_enum("quotes","status");
//$sql="SELECT quoteid, status, timestamp, project_name, name, phone_number, email, design_file";
//$sql="SELECT quoteid, status, timestamp, project_name, name, design_file";
$sql="SELECT quoteid, status, timestamp, project_name, name";
$sql.=" FROM quotes";
$sql.=" WHERE status!='incomplete'";
$sql.=" ORDER BY timestamp DESC";
$result=mysql_query($sql);

echo "<form name='quotes' method='post' action='/admin/quotes.php'>";
echo "<table cellpadding='3' cellspacing='0' border='1'>";

while($row=mysql_fetch_assoc($result)){
	$fieldnames=array_keys($row);
	if($count==0){
		echo "<tr>";
		for($i=0;$i<count($fieldnames);$i++){
			if($fieldnames[$i]=="name" || $fieldnames[$i]=="email"){}
			elseif($fieldnames[$i]=="project_name"){echo "<td><b>Name</b></td>";}
			elseif($fieldnames[$i]=="phone_number"){echo "<td><b>Contact Info</b></td>";}
			else{echo "<td><b>".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i])))."</b></td>";}
		}
		echo "<td><b>Design File(s)</b></td>";
		echo "</tr>";
	}
	
	echo "<tr valign=\"top\">";
	for($i=0;$i<count($fieldnames);$i++){
		if($fieldnames[$i]=="status"){
			echo "<td><select name=\"status_".$row["quoteid"]."\" onchange=\"document.quotes.quoteid.value='".$row["quoteid"]."';document.quotes.submit();\">";
			for($j=0;$j<count($vals);$j++){
				if($vals[$j]!='incomplete'){
					echo "<option value=\"".$vals[$j]."\"";
					if($vals[$j]==$row[$fieldnames[$i]]){echo " selected=\"selected\"";}
					echo ">".$vals[$j]."</option>";
				}
			}
			echo "</select>";
			echo "<br><input type=\"button\" value=\"delete quote\" onClick=\"if(confirm('Are you sure you want to completely delete this quote and all its design files from the server?')){document.location='/admin/quotes.php?action=delete_quote&quoteid=".$row["quoteid"]."';}\">";
			$sql="SELECT * FROM design_files WHERE quoteid='".$row["quoteid"]."'";
			$temp=mysql_fetch_array(mysql_unbuffered_query($sql));
			echo "</td>";
		}
		// NAME
		elseif($fieldnames[$i]=="project_name"){
			echo "<td>Project: ".formvalue($row[$fieldnames[$i]])."<br>";
		}
		elseif($fieldnames[$i]=="name"){
			echo "Client: ".formvalue($row[$fieldnames[$i]])."</td>";
		}
		// CONTACT INFO
		elseif($fieldnames[$i]=="phone_number"){
			echo "<td>Phone:&nbsp;".formvalue($row[$fieldnames[$i]])."<br>";
		}
		elseif($fieldnames[$i]=="email"){
			echo "Email:&nbsp;<a href=\"mailto:".formvalue($row[$fieldnames[$i]])."\">".formvalue($row[$fieldnames[$i]])."</a></td>";
		}
		elseif($fieldnames[$i]=="timestamp"){echo "<td>".str_replace(" ","<br>",date("m-d-y H:i",$row[$fieldnames[$i]]))."</td>";}
		elseif($fieldnames[$i]=="quoteid"){echo "<td><a href='/admin/quote.php?quoteid=".$row[$fieldnames[$i]]."'>".$row[$fieldnames[$i]]."</td>";}
		/*
		elseif($fieldnames[$i]=="design_file"){
			if(!$row[$fieldnames[$i]]){echo "<td>&nbsp;</td>";}
			else{echo "<td><a href='/admin/designfiles/".$row[$fieldnames[$i]]."' target='_blank'>".$row[$fieldnames[$i]]."</a></td>";}
		}
		*/
		else{echo "<td>".formvalue($row[$fieldnames[$i]])."&nbsp;</td>";}
		
		
	}
	// FIND DESIGN FILES
	$sql="SELECT * FROM design_files WHERE quoteid='".$row["quoteid"]."'";
	$temp=mysql_unbuffered_query($sql);
	$files="";
	while($t=mysql_fetch_array($temp)){
		if($files){$files.="<br>";}
		$files.="<a href='/admin/getdoc.php?fileid=".$t["fileid"]."' target='_blank'>".$t["name"]."</a>";
		//$files.="<a href='/admin/designfiles/file_".$t["fileid"].$t["ext"]."' target='_blank'>".$t["name"]."</a>";
		//$files.="<a href='/admin/getfile.php?fileid=".$t["fileid"]."' target='_blank'>".$t["name"]."</a>";
	}
	if($files){
		echo "<td>$files";
		echo "<br><input type=\"button\" value=\"delete design files\" onClick=\"if(confirm('Are you sure you want to delete this quote\'s design files from the server?')){document.location='/admin/quotes.php?action=delete_design_files&quoteid=".$row["quoteid"]."';}\">";
		echo "</td>";
	}
	else{echo "<td>&nbsp;</td>";}
	echo "</tr>";
	$count++;
}
echo "</table>";
echo "<input type='hidden' name='action' value='updatequote'/>";
echo "<input type='hidden' name='quoteid' value=''/>";
echo "</form>";
?>



















<?php include('../footer.php'); ?>