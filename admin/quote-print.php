<?php
	require_once('../header_php.php');

	








	$row=mysql_fetch_assoc(mysql_query("SELECT * FROM quotes WHERE quoteid='$quoteid'"));
	if(!$row){
		echo "ERROR: quote not found.";
		exit();
	}
?>

<html>
	<head>
		<style>
			body, td{
				font-family: verdana, arial;
				font-size: 14px;
			}
		</style>
	</head>
	<body>


<?php
	$fieldnames=array_keys($row);
	$vals=get_enum("quotes","status");
	
	
	echo "<table cellpadding='3' cellspacing='0' border='1'>";
	for($i=0;$i<count($fieldnames);$i++){
		echo "<tr valign='top'>";
		echo "<td>".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).":</td>";
		if($fieldnames[$i]=="timestamp"){echo "<td>".date("m-d-y H:i",$row[$fieldnames[$i]])."</td>";}
		
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
?>


<script>window.print();</script>
</body>
</html>
