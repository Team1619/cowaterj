<?php require_once('../header_php.php'); ?>












<script language='javascript'>
function verify_create(f){
	var msg='Your account could not be created due to the following error(s):\n';
	var index=1;
	
	if(trim(document.createaccount.password.value)==''){msg=msg+'\n('+index+') Please enter a password.';index=index+1;}
	if(!validemail(document.createaccount.email.value)){msg=msg+'\n('+index+') Please enter a valid email address.';index++;}
	if(trim(document.createaccount.name.value)==''){msg=msg+'\n('+index+') Please enter your name.';index++;}
	if(document.createaccount.phone_number.value.length!=12){msg=msg+'\n('+index+') Please enter a valid phone number.';index++;}
	if(document.createaccount.fax.value.length!=12){msg=msg+'\n('+index+') Please enter a valid fax number.';index++;}
	if(trim(document.createaccount.company.value)==''){msg=msg+'\n('+index+') Please enter the name of your company.';index++;}
	if(trim(document.createaccount.address.value)==''){msg=msg+'\n('+index+') Please enter a mailing address.';index++;}
	if(trim(document.createaccount.city.value)==''){msg=msg+'\n('+index+') Please enter a city.';index++;}
	if(trim(document.createaccount.state.value)==''){msg=msg+'\n('+index+') Please select a state.';index++;}
	if(document.createaccount.zip.value.length!=5){msg=msg+'\n('+index+') Please enter a valid zip code.';index++;}
		
	if(index>1){
		alert(msg); 
		return(false);
	}
	else{
		document.createaccount.action.value='createaccount';
		return(true);
	}
}
</script>








<?php include('../header.php'); ?>


<?php
	if($accountrow){
		echo "You are already logged into an account.";
		include('../footer.php');
		exit();
	}
		
	echo "<form name='createaccount' method='post' action='/account/index.php' onsubmit='return verify_create(this);'>";
	echo "<table>";
	// GET FIELDNAMES
	$sql="SELECT * FROM accounts LIMIT 1";
	$row=mysql_fetch_assoc(mysql_unbuffered_query($sql));
	$fieldnames=array_keys($row);
	for($i=0;$i<count($fieldnames);$i++){
		// FIELDS ACCOUNTID FIELD
		if($fieldnames[$i]!='accountid'){
			echo "<tr valign=\"top\">";
			echo "<td>".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).":</td>";
			echo "<td>";
			if($fieldnames[$i]=="password"){echo "<input type=\"password\" name=\"".$fieldnames[$i]."\" size=\"50\"/>";}
			elseif($fieldnames[$i]=="phone_number" || $fieldnames[$i]=="fax"){echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" size=\"50\" onkeyup=\"inputmask(this.value,this,'phone');\" maxlength=\"12\"/>";}
			elseif($fieldnames[$i]=="zip"){echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" size=\"50\" onkeyup=\"inputmask(this.value,this,'zip');\" maxlength=\"5\"/>";}
			elseif(get_field_type("accounts",$fieldnames[$i])=="enum"){
				echo "<select name=\"".$fieldnames[$i]."\">";
				$vals=get_enum("accounts",$fieldnames[$i]);
				for($j=0;$j<count($vals);$j++){
					echo "<option value=\"".$vals[$j]."\"";
					echo ">".$vals[$j]."</option>";
				}
				echo "</select>";
			}
			else{echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" size=\"50\"/>";}
			echo "</td>";
			echo "</tr>";
		}
	}
		
		
	echo "<tr><td>&nbsp;</td><td><input type='submit' name='submitaccount' value='Create Account'/></td></tr>";
	echo "</table>";
	echo "<input type='hidden' name='action' value=''/>";
	echo "</form>";


?>




<?php include('../footer.php'); ?>