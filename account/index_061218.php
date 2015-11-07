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
			@mysql_query("INSERT INTO accounts (email,password,name,phone_number) VALUES (\"".trim($email)."\",\"".trim($password)."\",\"".trim($name)."\",\"".trim($phone_number)."\")");
			$accountid=mysql_insert_id();
			@mysql_query("DELETE FROM visitors_accounts WHERE visitorid='$visitorid'");
			@mysql_query("INSERT INTO visitors_accounts (visitorid,accountid) VALUES ('$visitorid','$accountid')");
			$accountrow=getaccountrow();
		}
	}
	elseif($action=='updateaccount'){
		// MAKE SURE NEW EMAIL DOESN'T ALREADY EXIST
		$row=mysql_fetch_array(mysql_unbuffered_query("SELECT accountid FROM accounts WHERE email=\"".trim($email)."\" AND accountid!=\"".$accountrow["accountid"]."\""));
		if($row["accountid"]){$error="Another account is already using this email address ($email).";}
		else{
			$sql="UPDATE accounts SET password=\"".trim($password)."\"";
			$sql.=", email=\"".trim($email)."\"";
			$sql.=", name=\"".trim($name)."\"";
			$sql.=", phone_number=\"".trim($phone_number)."\"";
			$sql.=" WHERE accountid=\"".$accountrow["accountid"]."\"";
			@mysql_query($sql);
			$accountrow=getaccountrow();
		}
	}
?>










<script language='javascript'>
function verify_create(f){
	var msg='Your account could not be created due to the following error(s):\n';
	var index=1;
	
	if(trim(document.createaccount.password.value)==''){msg=msg+'\n('+index+') Please enter a password.';index=index+1;}
	if(!validemail(document.createaccount.email.value)){msg=msg+'\n('+index+') Please enter a valid email address.';index++;}
	if(trim(document.createaccount.name.value)==''){msg=msg+'\n('+index+') Please enter your name.';index++;}
	if(document.createaccount.phone_number.value.length!=12){msg=msg+'\n('+index+') Please enter a valid phone number.';index++;}

	
	if(index>1){
		alert(msg); 
		return(false);
	}
	else{
		document.createaccount.action.value='createaccount';
		return(true);
	}
}

function verify_update(f){
	var msg='Your account could not be updated due to the following error(s):\n';
	var index=1;

	
	if(trim(document.updateaccount.password.value)==''){msg=msg+'\n('+index+') Please enter a password.';index=index+1;}
	if(!validemail(document.updateaccount.email.value)){msg=msg+'\n('+index+') Please enter a valid email address.';index++;}
	if(trim(document.updateaccount.name.value)==''){msg=msg+'\n('+index+') Please enter your name.';index++;}
	if(document.updateaccount.phone_number.value.length!=12){msg=msg+'\n('+index+') Please enter a valid phone number.';index++;}
	
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
		echo "Log in, or create an account below";
		echo "<form name='login' method='post' action='/account/index.php'>";
		echo "<table>";
		echo "<tr><td>Email:</td><td><input type='text' name='email'/></td></tr>";
		echo "<tr><td>Password:</td><td><input type='password' name='password'/></td></tr>";
		echo "<tr><td>&nbsp;</td><td><input type='submit' name='submitlogin' value='Log In'/></td></tr>";
		echo "<tr><td colspan='2'><em>(If you have forgotten your password <a href='/account/password.php'>click here</a> to have it emailed to you.)</em></td></tr>";
		echo "</table>";
		
		echo "<input type='hidden' name='action' value='login'/>";
		echo "</form>";
		
		echo "<br><br>";

		
		echo "<form name='createaccount' method='post' action='/account/index.php' onsubmit='return verify_create(this);'>";
		echo "<table>";
		echo "<tr><td>Email:</td><td><input type='text' name='email'/></td></tr>";
		echo "<tr><td>Password:</td><td><input type='password' name='password'/></td></tr>";
		echo "<tr><td>Name:</td><td><input type='text' name='name'/></td></tr>";
		echo "<tr><td>Phone Number:</td><td><input type='text' name='phone_number' onkeyup=\"inputmask(this.value,this,'phone');\" maxlength='12'/></td></tr>";
		echo "<tr><td>&nbsp;</td><td><input type='submit' name='submitaccount' value='Create Account'/></td></tr>";
		echo "</table>";
		echo "<input type='hidden' name='action' value=''/>";
		echo "</form>";
		
		include('../footer.php');
		exit();
	}


	echo "<form name='updateaccount' method='post' action='/account/index.php' onsubmit='return verify_update(this);'>";
	echo "<table>";
	echo "<tr><td>Email:</td><td><input type='text' name='email' value=\"".formvalue($accountrow["email"])."\"/></td></tr>";
	echo "<tr><td>Password:</td><td><input type='password' name='password' value=\"".formvalue($accountrow["password"])."\"/></td></tr>";
	echo "<tr><td>Name:</td><td><input type='text' name='name' value=\"".formvalue($accountrow["name"])."\"/></td></tr>";
	echo "<tr><td>Phone Number:</td><td><input type='text' name='phone_number' value=\"".formvalue($accountrow["phone_number"])."\"  onkeyup=\"inputmask(this.value,this,'phone');\" maxlength='12'/></td></tr>";
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