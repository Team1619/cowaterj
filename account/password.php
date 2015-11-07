<?php require_once('../header_php.php'); ?>
<?php include('../header.php'); ?>



<?php
	if($_POST['action']=="sendpassword"){
		$rs=$mysqli->query("SELECT name, email, password FROM accounts WHERE email=\"".$mysqli->real_escape_string($_POST['email'])."\"");
		$row=$rs->fetch_assoc();
		if($row["password"]){
			echo "Your password has been emailed to $email.";
			mail($_POST['email'],"coloradowaterjet.com account info","Dear ".$row["name"].",\n\nTo log into your coloradowaterjet.com account please use the following information:\n\nEmail: ".$row['email']."\nPassword: ".$row["password"]."\n\nPlease contact us if you have any questions.\n\nCheers,\nColorado Water Jet","FROM: info@coloradowaterjet.com"); 
			include('../footer.php');
			exit();
		}
		else{
			echo "A password matching the email address you provided (".$_POST['email'].") could not be found.  If you feel you have received this message in error please <a href='/contact.php'>contact us</a>.  If you don't have an account, <a href='/account/index.php'>click here</a> to create one.";
		}
	}

?>



<script>
	function verify(f){
		if(!validemail(document.sendpassword.email.value)){
			alert('Please enter a valid email address.');
			return(false);
		}
		document.sendpassword.action.value='sendpassword';
		return(true);
	}
</script>



<form name='sendpassword' method='post' action='/account/password.php' onsubmit='return verify(this);'>
<input type='text' name='email'/>&nbsp;<input type='submit' name='mysubmit' value='get password'/>
<input type='hidden' name='action' value=''/>
</form>



<?php include('../footer.php'); ?>