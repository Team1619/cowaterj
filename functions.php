<?php
	function formvalue($input){return(stripslashes(str_replace('"','&#34;',$input)));}	
	function strlike($mystring,$findme){
		$pos=strpos(strtolower($mystring),strtolower($findme));
		if($pos===false){return(false);}
		else{return(true);}
	}
	
	function checkemail($email){
 		if(!preg_match("/^( [a-zA-Z0-9] )+( [a-zA-Z0-9\._-] )*@( [a-zA-Z0-9_-] )+( [a-zA-Z0-9\._-] +)+$/" , $email)){return false;}
 		return true;
	}	
	function receipt($quoteid,$format='html'){
		$row=mysql_fetch_assoc(mysql_unbuffered_query("SELECT * FROM quotes WHERE quoteid='$quoteid'"));
		if(!$row){return(false);}
		$fieldnames=array_keys($row);
		$msg="";
		
		if($format=='html'){$msg.="<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\">";}
		
		for($i=0;$i<count($fieldnames);$i++){
			// FIELDS TO SPACE OUT
			if($fieldnames[$i]=='project_name'){
				if($format=='html'){$msg.="<tr><td colspan=\"2\">&nbsp;</td></tr>";}
				else{$msg.="\n";}
			}
			// FIELDS TO IGNORE
			if($fieldnames[$i]=='visitorid' || $fieldnames[$i]=='accountid' || $fieldnames[$i]=='timestamp' || $fieldnames[$i]=='status' || $fieldnames[$i]=='design_file'){}
			elseif(strlike($fieldnames[$i],'materials_supplied')){
				if($format=='html'){
					$msg.="<tr><td>".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).":&nbsp;&nbsp;</td><td>";
					if($row[$fieldnames[$i]]){$msg.="yes";}
					else{$msg.="no";}
					$msg.="</td></tr>";
				}
				else{
					$msg.=str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).": ";
					if($row[$fieldnames[$i]]){$msg.="yes";}
					else{$msg.="no";}
					$msg.=$row[$fieldnames[$i]]."\n";
				}
			}
			else{
				if($format=='html'){$msg.="<tr><td>".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).":&nbsp;&nbsp;</td><td>".nl2br($row[$fieldnames[$i]])."</td></tr>";}
				else{$msg.=str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).": ".$row[$fieldnames[$i]]."\n";}
			}
		}
		// SPIT OUT FILE NAMES
		$result=mysql_query("SELECT name FROM design_files WHERE quoteid='$quoteid'");
		if(mysql_num_rows($result)){
			if($format=='html'){$msg.="<tr><td>&nbsp;<br>Design Files:</td><td>&nbsp;";}
			else{$msg.="\nDesign Files:\n";}
			while($row=mysql_fetch_array($result)){
				if($format=='html'){$msg.="<br>";}
				else{$msg.="\n";}
				$msg.=$row["name"];
			}
			if($format=='html'){$msg.="</td></tr>";}
		}
		if($format=='html'){$msg.="</table>";}
		return($msg);
	}		
	// FUNCTION FOR FINDING ENUMERATED FIELD VALUES:
	function get_enum($tablename,$fieldname){
		$sql="SHOW COLUMNS FROM $tablename LIKE '$fieldname'";
		$result=mysql_query($sql);
		// WE COULD TRY UNBUFFERED IF WE CAN DO THE IF STATEMENT ON $row[1]
		if(mysql_num_rows($result)>0){
			$row=mysql_fetch_row($result);
			mysql_free_result($result);
			$options=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$row[1]));
			return($options);	
		}
		mysql_free_result($result);
		return(false);
	}		
	function is_enum($tablename,$fieldname){
		$sql="SHOW COLUMNS FROM $tablename LIKE '$fieldname'";
		$result=mysql_query($sql);
		// WE COULD TRY UNBUFFERED IF WE CAN DO THE IF STATEMENT ON $row[1]
		if(mysql_num_rows($result)>0){
			$row=mysql_fetch_row($result);
			mysql_free_result($result);
			if(strlike($row[1],"enum")){return(true);}	
		}
		return(false);
	}
	
	function get_field_type($tablename,$fieldname){
		$sql="SHOW COLUMNS FROM $tablename LIKE '$fieldname'";
		$result=mysql_query($sql);
		$type=false;
		// WE COULD TRY UNBUFFERED IF WE CAN DO THE IF STATEMENT ON $row[1]
		if(mysql_num_rows($result)>0){
			$row=mysql_fetch_row($result);
			$type=$row[1];
			if(strlike($row[1],"(")){$type=substr($row[1],0,strpos($row[1],"("));}
		}
		return($type);
	}		
	function mytime(){
		global $this_time;
		return($this_time->getTime());
	}

	function visitorid_set(){
		$duration=60*60*24*30;
		global $visitorid;
		$timestamp=mytime();
		
		// IF NO VISITOR ID THEN CREATE ONE AND FILL VISITORS TABLE
		if(!$visitorid){$visitorid=$_COOKIE["coloradowaterjet_id"];}
		if(!$visitorid){
			@mysql_query("insert into visitors (timestamp) VALUES ('$timestamp')");
			$visitorid=mysql_insert_id();
		}
		// CHECK TO MAKE SURE THAT ROW EXISTS IN VISITORS TABLE
		$row=mysql_fetch_array(mysql_unbuffered_query("SELECT visitorid FROM visitors WHERE visitorid='$visitorid'"));
		if($visitorid && !$row["visitorid"]){@mysql_query("INSERT INTO visitors (visitorid,timestamp) VALUES ('$visitorid','$timestamp')");}
		// UPDATE EVERYTHING
		@mysql_query("UPDATE visitors SET timestamp='$timestamp' WHERE visitorid='$visitorid'");
		if(!headers_sent()){setcookie("coloradowaterjet_id",$visitorid,$timestamp+$duration,"/","",0);}
		// REMOVE OLD CRAP
		$result=mysql_unbuffered_query("SELECT visitorid FROM visitors WHERE timestamp<'".($timestamp-$duration)."'");
		$oldvisitors="";
		while($row=mysql_fetch_array($result)){
			if($oldvisitors){$oldvisitors.=",";}
			$oldvisitors.="'".$row["visitorid"]."'";
		}
		@mysql_unbuffered_query("DELETE FROM visitors_accounts WHERE visitorid IN ($oldvisitors)");
		@mysql_unbuffered_query("DELETE FROM visitors WHERE visitorid IN ($oldvisitors)");
	
		return($visitorid);
	}	
	function getaccountrow(){
		global $visitorid;
		$accountrow=mysql_fetch_array(mysql_unbuffered_query("SELECT * FROM accounts,visitors_accounts WHERE visitors_accounts.accountid=accounts.accountid AND visitors_accounts.visitorid='$visitorid'"));
		return($accountrow);
	}	
	function mkthumb($filename,$maxdimension){
		$filename=$_SERVER['DOCUMENT_ROOT']."/gallery/".$filename;
		$filename_new=substr($filename,0,-4)."_th.jpg";
		// Set a maximum height and width
		$width=$maxdimension;
		$height=$maxdimension;		
		// Content type
		//header('Content-type: image/jpeg');
		// Get new dimensions
		list($width_orig, $height_orig) = getimagesize($filename);
		$ratio_orig = $width_orig/$height_orig;
		if($width/$height>$ratio_orig){$width = $height*$ratio_orig;}
		else{$height = $width/$ratio_orig;}		
		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromjpeg($filename);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		// Output
		imagejpeg($image_p,$filename_new,100);
	}
?>