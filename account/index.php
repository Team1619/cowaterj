<?php require_once('../header_php.php'); ?>

<?php
	if($action=='login' && $password && $email){
		$row=mysql_fetch_array(mysql_unbuffered_query("SELECT accountid FROM accounts WHERE email=\"$email\" AND password=\"$password\""));
		if($row["accountid"]){
			@mysql_query("DELETE FROM visitors_accounts WHERE visitorid='$visitorid'");
			@mysql_query("INSERT INTO visitors_accounts (visitorid,accountid) VALUES ('$visitorid','".$row["accountid"]."')");
			$accountrow=getaccountrow();
		}
		else{$error="An account matching this email and password does not exist. If you have forgotten your password <a href='/account/password.php'>click here</a> to have it emailed to you.";}
	}
	elseif($action=='createaccount'){
		// CHECK TO SEE IF AN ACCOUNT MATCING THIS EMAIL ALREADY EXITS
		$row=mysql_fetch_array(mysql_unbuffered_query("SELECT accountid FROM accounts WHERE email=\"$email\""));
		if($row["accountid"]){$error="An account matching this email already exists.";}
		else{
			// CREATE ACCOUNT
			@mysql_query("INSERT INTO accounts (email,password) VALUES (\"".trim($email)."\",\"".trim($password)."\")");
			$accountid=mysql_insert_id();
			// UPDATE ACCOUNT
			$sql="UPDATE accounts SET ";
			$junk=mysql_fetch_assoc(mysql_unbuffered_query("SELECT * FROM accounts WHERE accountid='$accountid'"));
			$fieldnames=array_keys($junk);
			for($i=0;$i<count($fieldnames);$i++){
				if($i>0){$sql.=", ";}
				eval("\$sql.=".$fieldnames[$i].".\"=\\\"\$".$fieldnames[$i]."\\\"\";");
			}
			$sql.=" WHERE accountid=\"$accountid\"";
			@mysql_query($sql);
			// LOG IN
			@mysql_query("DELETE FROM visitors_accounts WHERE visitorid='$visitorid'");
			@mysql_query("INSERT INTO visitors_accounts (visitorid,accountid) VALUES ('$visitorid','$accountid')");
			// GET UPDATE ACCOUNTINFO
			$accountrow=getaccountrow();
		}
	}
	elseif($action=='updateaccount'){
		// MAKE SURE NEW EMAIL DOESN'T ALREADY EXIST
		$row=mysql_fetch_array(mysql_unbuffered_query("SELECT accountid FROM accounts WHERE email=\"".trim($email)."\" AND accountid!=\"".$accountrow["accountid"]."\""));
		if($row["accountid"]){$error="Another account is already using this email address ($email).";}
		else{
			$sql="UPDATE accounts SET ";
			$junk=mysql_fetch_assoc(mysql_unbuffered_query("SELECT * FROM accounts WHERE accountid='$accountid'"));
			$fieldnames=array_keys($junk);
			for($i=0;$i<count($fieldnames);$i++){
				if($i>0){$sql.=", ";}
				eval("\$sql.=".$fieldnames[$i].".\"=\\\"\$".$fieldnames[$i]."\\\"\";");
			}
			$sql.=" WHERE accountid=\"$accountid\"";
			@mysql_query($sql);
			$accountrow=getaccountrow();
		}
	}
?>










<script language='javascript'>
/*
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
*/

function verify_update(f){
	var msg='Your account could not be updated due to the following error(s):\n';
	var index=1;

	if(trim(document.updateaccount.password.value)==''){msg=msg+'\n('+index+') Please enter a password.';index=index+1;}
	if(!validemail(document.updateaccount.email.value)){msg=msg+'\n('+index+') Please enter a valid email address.';index++;}
	if(trim(document.updateaccount.name.value)==''){msg=msg+'\n('+index+') Please enter your name.';index++;}
	if(document.updateaccount.phone_number.value.length!=12){msg=msg+'\n('+index+') Please enter a valid phone number.';index++;}
	if(document.updateaccount.fax.value.length!=12){msg=msg+'\n('+index+') Please enter a valid fax number.';index++;}
	if(trim(document.updateaccount.company.value)==''){msg=msg+'\n('+index+') Please enter the name of your company.';index++;}
	if(trim(document.updateaccount.address.value)==''){msg=msg+'\n('+index+') Please enter a mailing address.';index++;}
	if(trim(document.updateaccount.city.value)==''){msg=msg+'\n('+index+') Please enter a city.';index++;}
	if(trim(document.updateaccount.state.value)==''){msg=msg+'\n('+index+') Please select a state.';index++;}
	if(document.updateaccount.zip.value.length!=5){msg=msg+'\n('+index+') Please enter a valid zip code.';index++;}
		
	if(index>1){
		alert(msg); 
		return(false);
	}
	else{
		document.updateaccount.action.value='updateaccount';
		return(true);
	}
}
</script>








<?php include('../header.php'); ?>


<?php
	if(!$accountrow){
		echo "If you have forgotten your password <a href='/account/password.php'>click here</a> to have it emailed to you.";
		echo "<br><br>If you do not have an account to <a href=\"/account/create.php\">click here</a> to create one.  An account is not required to request a quote, but the benefits of an account include (a) our system remembers your settings for quicker quote requests in the future, and (b) you will be able to track a record of your quote requests.";
		
		echo "<form name='login' method='post' action='/account/index.php'>";
		echo "<table>";
		echo "<tr><td>Email:</td><td width=\"*\"><input type='text' name='email'/></td></tr>";
		echo "<tr><td>Password:</td><td><input type='password' name='password'/></td></tr>";
		echo "<tr><td>&nbsp;</td><td><input type='submit' name='submitlogin' value='Log In'/></td></tr>";
		echo "</table>";
		
		echo "<input type='hidden' name='action' value='login'/>";
		echo "</form>";

		include('../footer.php');
		exit();
	}


	echo "<form name='updateaccount' method='post' action='/account/index.php' onsubmit='return verify_update(this);'>";
	echo "<table>";
	
	// GET FIELDNAMES
	$sql="SELECT * FROM accounts WHERE accountid='".$accountrow["accountid"]."'";
	$row=mysql_fetch_assoc(mysql_unbuffered_query($sql));
	$fieldnames=array_keys($row);
	for($i=0;$i<count($fieldnames);$i++){
		// FIELDS ACCOUNTID FIELD
		if($fieldnames[$i]=='accountid'){
			echo "<input type=\"hidden\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($row[$fieldnames[$i]])."\"/>";
		}
		else{
			echo "<tr valign=\"top\">";
			echo "<td>".str_replace(" ","&nbsp;",ucwords(str_replace("_"," ",$fieldnames[$i]))).":</td>";
			echo "<td>";
			if($fieldnames[$i]=="password"){echo "<input type=\"password\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($row[$fieldnames[$i]])."\" size=\"60\"/>";}
			elseif($fieldnames[$i]=="phone_number" || $fieldnames[$i]=="fax"){echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($row[$fieldnames[$i]])."\" size=\"60\" onkeyup=\"inputmask(this.value,this,'phone');\" maxlength=\"12\"/>";}
			elseif($fieldnames[$i]=="zip"){echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($row[$fieldnames[$i]])."\" size=\"60\" onkeyup=\"inputmask(this.value,this,'zip');\" maxlength=\"5\"/>";}
			elseif(get_field_type("accounts",$fieldnames[$i])=="enum"){
				echo "<select name=\"".$fieldnames[$i]."\">";
				$vals=get_enum("accounts",$fieldnames[$i]);
				for($j=0;$j<count($vals);$j++){
					echo "<option value=\"".$vals[$j]."\"";
					if($vals[$j]==$row[$fieldnames[$i]]){echo " selected=\"selected\"";}
					echo ">".$vals[$j]."</option>";
				}
				echo "</select>";
			}
			else{echo "<input type=\"text\" name=\"".$fieldnames[$i]."\" value=\"".formvalue($row[$fieldnames[$i]])."\" size=\"60\"/>";}
			echo "</td>";
			echo "</tr>";
		}
	}
	echo "<tr><td>&nbsp;</td><td><input type='submit' name='updateaccount' value='Update Account'/></td></tr>";
	echo "</table>";
	echo "<input type='hidden' name='action' value=''/>";
	echo "</form>";






	$result=mysql_query("SELECT quoteid,timestamp FROM quotes WHERE accountid='".$accountrow["accountid"]."' AND status!='incomplete' ORDER BY timestamp DESC");
	if(mysql_num_rows($result)>0){
		echo "<hr>";
		echo "<div class='pagetitle'>Quote History</div>";
		echo "<table cellpadding='2' cellspacing='0' border='1'>";
		echo "<tr><td align='center'><b>Date</b></td><td align='center'><b>Quote ID</b></td></tr>";
		while($row=mysql_fetch_array($result)){
			echo "<tr><td align='center'>".date("m-d-Y",$row["timestamp"])."</td><td align='right'><a href='/account/quote.php?quoteid=".$row["quoteid"]."'>".$row["quoteid"]."</a></td></tr>";
		}
		echo "</table>";
	}










?>





<?php include('../footer.php'); ?>