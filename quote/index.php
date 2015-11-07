<?php require_once('../header_php.php'); ?>

<?php include('../header.php'); ?>


<?php
if(isset($_POST['email'])){

	
	$msg="Name: ".trim(stripslashes($name))."\n";
	if($accountrow["accountid"]){$msg.="Account ID: ".$accountrow["accountid"]."\n";}
	$msg.="Email: ".trim($email)."\n";
	$msg.="Phone: ".trim($phone)."\n";
	$msg.="Company: ".trim($company)."\n\n";
	$msg.=trim(stripslashes($comments));

	if(strlike($msg,"cc:")){
		$error="Your message could not be sent.  The string \"cc:\" is not allowed.  Please try again.";
		echo $error;
	}
	else{
		@mysql_query("INSERT INTO contacts (accountid,timestamp,name,email,phone,company,comments) VALUES (\"".$accountrow["accountid"]."\",\"".mytime()."\",\"$name\",\"$email\",\"$phone\",\"$company\",\"$comments\")");
		//mail("miles@oldtownmediainc.com","coloradowaterjet.com contact",$msg);
		mail("jdvm81@gmail.comm","coloradowaterjet.com contact",$msg,"From: $email");
		mail("rfq@coloradowaterjet.com","coloradowaterjet.com contact",$msg,"From: $email");
		mail("sales@coloradowaterjet.com", "coloradowaterjet.com contact",$msg,"From: $email");
		echo "Thank you for submitting the quote form.  We will get in touch with you as soon as possible.";
		include('../footer.php');
		exit();
	}
}

?>


<p>Please email us directly at <a href="mailto:Sales@ColoradoWaterJet.com?Subject=Quote" target="_top">Sales@ColoradoWaterJet.com</a>.</p>
<p>Please include the following information:</p>
<ul>
<li>Name</li>
<li>Email</li>
<li>Phone Number</li>
<li>Company</li>
<li>Details About Your Project</li>
</ul>


<script language="javascript">
function verify(f){
	var msg='The form could not be submitted due to the following error(s):\n';
	var index=1;
	
	if(trim(document.contact.name.value)==''){msg=msg+'('+index+') Please enter your name\n';index=index+1;}
	if(!validemail(trim(document.contact.email.value))){msg=msg+'('+index+') Please enter a valid email address.\n';index=index+1;}
	if(trim(document.contact.comments.value)==''){msg=msg+'('+index+') Please enter a message.\n';index=index+1;}
	if(document.contact.phone.value.length<10){msg=msg+'('+index+') Please enter a valid telephone number.\n';index=index+1;}
	
	if(index>1){
		alert(msg); 
		return(false);
	}
	else{
		document.contact.action.value='contact';
		return(true);
	}
}
</script>

<!--<form name='contact' method='post' action='#' onsubmit='return verify(this);'>
<table>
<tr><td>Name:</td><td><input type='text' name='name'/></td></tr>
<tr><td>Email:</td><td><input type='text' name='email'/></td></tr>
<tr><td>Phone Number:</td><td><input type='text' name='phone' onkeyup="inputmask(this.value,this,'phone');" maxlength='12'/></td></tr>
<tr><td>Company:</td><td><input type='text' name='company'/></td></tr>
<tr valign='top'><td>Details About Your Project:</td><td><textarea name='comments' cols='30' rows='5'></textarea></td></tr>
<tr><td>&nbsp;</td><td><input type='submit' name='mysubmit' value='Send Message'/></td></tr>
</table>
<input type='hidden' name='action' value=''/>
<input type='hidden' name='accountid' value='<?=$accountrow["accountid"]?>'/>
</form>-->





<?php include('../footer.php'); ?>