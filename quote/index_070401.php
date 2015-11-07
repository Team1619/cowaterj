<?php 
	include('../header.php'); 
	echo "Our request a quote form is undergoing routine maintenance.  Sorry for the inconvenience.  Please give us a call at 970.532.5404 or check back in a couple of hours.";
	include('../footer.php');
	exit();
?>





<script>
	function verify(f){
		var msg='Your quote request could not be sent due to the following issue(s).  Please correct them and try again.\n';
		var index=1;
		

		// REQUIRE ACCOUNT INFO
		if(!validemail(document.quote1.email.value)){msg=msg+'\n('+index+') Please enter a valid email address.';index++;}
		if(trim(document.quote1.name.value)==''){msg=msg+'\n('+index+') Please enter your name.';index++;}
		if(document.quote1.phone_number.value.length!=12){msg=msg+'\n('+index+') Please enter a valid phone number.';index++;}
		//if(document.quote1.fax.value.length!=12){msg=msg+'\n('+index+') Please enter a valid fax number.';index++;}
		//if(trim(document.quote1.company.value)==''){msg=msg+'\n('+index+') Please enter the name of your company.';index++;}
		//if(trim(document.quote1.address.value)==''){msg=msg+'\n('+index+') Please enter a mailing address.';index++;}
		//if(trim(document.quote1.city.value)==''){msg=msg+'\n('+index+') Please enter a city.';index++;}
		//if(trim(document.quote1.state.value)==''){msg=msg+'\n('+index+') Please select a state.';index++;}
		//if(document.quote1.zip.value.length!=5){msg=msg+'\n('+index+') Please enter a valid zip code.';index++;}

		
		// REQUIRE QUOTE INFO
		if(trim(document.quote1.project_name.value)==''){msg=msg+'\n('+index+') Please enter a project name.';index++;}
		if(trim(document.quote1.quantity.value)==''){msg=msg+'\n('+index+') Please enter a quantity.';index++;}
				
		// REQUIRE .ZIP, .AI, .EPS, .DXF, .SCV FILES ONLY
		var last3=document.quote1.design_file.value;
		if(last3!=''){
			last3=last3.substr(last3.length-3);
			last3=last3.toUpperCase();
			if(!(last3=='ZIP' || last3=='.AI' || last3=='EPS' || last3=='DXF' || last3=='SCV')){msg=msg+'\n('+index+') Please upload a valid design file.  Only .zip, .ai, .eps., .dxf, and .scv files are allowed.';index++;}
		}
				
		if(index>1){
			alert(msg);
			return(false);
		}
		document.quote1.action.value='quote2';
		return(true);
	}

</script>










<?php
	$inputsize=43;

	if(!$accountrow){
		echo "Remember, if you have an account and <a href='/account/index.php'>log into it</a> the quote form below will remember your settings.";
		echo "<br><br>";
		echo "If you don't have an account, you may wish to <a href='/account/create.php'>create one</a>.  The benefits of having an account include (a) our system remembers your settings for quicker quote requests in the future, and (b) you will be able to track a record of your quote requests.";
		echo "<br><br>";
	}


	
	$quote=mysql_fetch_assoc(mysql_unbuffered_query("SELECT * FROM quotes WHERE visitorid='$visitorid'"));
	if($quote){@mysql_query("UPDATE quotes SET timestamp=\"".mytime()."\", accountid=\"".$accountrow["accountid"]."\", status=\"incomplete\" WHERE visitorid='$visitorid'");}
	else{@mysql_query("INSERT INTO quotes (visitorid,accountid,timestamp,status) VALUES ('$visitorid','".$accountrow["accountid"]."','".mytime()."','incomplete')");}
	$quote=mysql_fetch_assoc(mysql_unbuffered_query("SELECT * FROM quotes WHERE visitorid='$visitorid'"));


	echo "Please complete the quote request form in as much detail as possible and click the \"Request Quote\" button to submit your request.  We strive to turn quotes around in two business days.";	
	echo "<form name=\"quote1\" method=\"post\" action=\"/quote/quote2.php\" onsubmit=\"return verify(this);\" enctype=\"multipart/form-data\">";
	echo "<table>";
	
	$account=mysql_fetch_assoc(mysql_unbuffered_query("SELECT * FROM accounts WHERE accountid='".$accountrow["accountid"]."'"));
	$fieldnames=array_keys($quote);
	for($i=0;$i<count($fieldnames);$i++){
		
		// FIRST PICK OUT HIDDEN FIELDS
		if($fieldnames[$i]=="visitorid" || $fieldnames[$i]=="accountid" || $fieldnames[$i]=="status"){
			echo "<input type=\"hidden\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($account[$fieldnames[$i]])."\"/>";
		}
		// THEN LOOK AT FIELDS WE WILL SET
		elseif($fieldnames[$i]=="timestamp"){echo "<input type='hidden' name=\"".$fieldnames[$i]."\" value='".mytime()."'/>";}
		elseif($fieldnames[$i]=="quoteid"){echo "<input type='hidden' name=\"".$fieldnames[$i]."\" value='".$quote["quoteid"]."'/>";}
		elseif($fieldnames[$i]=="materials_supplied_by_customer" || $fieldnames[$i]=="materials_supplied_by_colorado_waterjet"){
			echo "<tr><td colspan=\"2\">".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i])))."&nbsp;<input type='checkbox' name='".$fieldnames[$i]."' value='1'/></td></tr>";
		}
		// THEN LOOK AT FIELDS WE WILL IGNORE
		// THEN LOOK AT TEXT INPUTS
		else{
			echo "<tr valign=\"top\">";
			echo "<td>".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).":";
			// CHECK REQUIRED FIELD
			if($fieldnames[$i]=="email" || $fieldnames[$i]=="phone_number" || $fieldnames[$i]=="name" || $fieldnames[$i]=="project_name" || $fieldnames[$i]=="quantity"){
				echo "<font color=\"#B80009\"><sup>*</sup></font>";
			}
			echo "&nbsp;</td>";
			echo "<td>";
			if($fieldnames[$i]=="design_file"){echo "<input type=\"file\" name=\"".$fieldnames[$i]."\"/><br><i>Please review our drawing <a href=\"#\" onclick=\"window.open('/filespecs.php','filespecs','status=0,toolbar=0,location=0,resizeable=1,scrollbars=1,menubar=0,directories=0,height=500,width=600');\">file requirements</a>.</i>";}
			elseif($fieldnames[$i]=="phone_number" || $fieldnames[$i]=="fax"){echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($account[$fieldnames[$i]])."\" size=\"$inputsize\" onkeyup=\"inputmask(this.value,this,'phone');\" maxlength=\"12\"/>";}
			elseif($fieldnames[$i]=="zip"){echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($account[$fieldnames[$i]])."\" size=\"$inputsize\" onkeyup=\"inputmask(this.value,this,'zip');\" maxlength=\"5\"/>";}
			elseif(get_field_type("quotes",$fieldnames[$i])=="text"){echo "<textarea name=\"".$fieldnames[$i]."\" cols=\"40\" rows=\"7\">".formvalue($account[$fieldnames[$i]])."</textarea>";}
			elseif(get_field_type("quotes",$fieldnames[$i])=="int"){echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($account[$fieldnames[$i]])."\" size=\"$inputsize\" maxlength=\"7\" onkeyup=\"inputmask(this.value,this,'int');\"/>";}
			elseif(get_field_type("quotes",$fieldnames[$i])=="enum"){
				echo "<select name=\"".$fieldnames[$i]."\">";
				$vals=get_enum("quotes",$fieldnames[$i]);
				for($j=0;$j<count($vals);$j++){
					echo "<option value=\"".$vals[$j]."\"";
					if($vals[$j]==$account[$fieldnames[$i]]){echo " selected=\"selected\"";}
					echo ">".$vals[$j]."</option>";
				}
				echo "</select>";
			}
			//elseif(get_field_type("quotes",$fieldnames[$i])=="tinyint"){echo "<input type='checkbox' name='".$fieldnames[$i]."' value='1'/>";}
			else{echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($account[$fieldnames[$i]])."\" size=\"$inputsize\"/>";}
			echo "</td></tr>\n";
		}
	}
	echo "<tr><td>&nbsp;</td><td><input type=\"submit\" name=\"mysubmit\" value=\"Request Quote\"/></td></tr>";
	
	echo "</table>";
	echo "<input type='hidden' name='MAX_FILE_SIZE' value='5000000'/>";
	echo "<input type='hidden' name='action' value=''/>";
	echo "<input type='hidden' name='accountid' value='".$accountrow["accountid"]."'/>";
	echo "</form>";
?>














<?php include('../footer.php'); ?>