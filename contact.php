<?php require_once('header_php.php'); ?>

<?php
$map = new GoogleMapAPI('map');
	$map->setAPIKey($googlemaps_key);
	$map->disableSidebar();
	$map->setMapType('map');
	$map->setWidth(510);
	$map->setHeight(350);
	$map->setMapType('hybrid');
	$map->disableDirections();
	$map->setZoomLevel(13);
	//$map->addMarkerByAddress('5186-F Longs Peak Road Berthoud Colorado 80513','','Colorado Water Jet<br>5186-F Longs Peak Road<br>Berthoud, Colorado 80513');
	$map->addMarkerByCoords(-104.98301267623901,40.34088546651259,'','Colorado Water Jet<br>5186 Longs Peak Road Unit F<br>Berthoud, Colorado 80513'); 
?>


<?php include('header.php'); ?>




<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr valign='top'><td rowspan='3'>Colorado Water Jet<br>5186 Longs Peak Road Unit F<br>Berthoud, Colorado 80513</td><td rowspan='3'><img src="/images/clear.gi" height="1" width="60"></td>
<td>Phone:</td><td>970-532-5404</td></tr>
<tr><td>Toll Free:&nbsp;</td><td>866-532-5404</td></tr>
<!--<tr><td>Fax:</td><td>970-532-5405</td></tr>-->
</table>&nbsp;<br>



<div align="center"><?php $map->printMap(); ?></div>









<?php
if($action=='contact'){

	
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
		//mail("aaleach@gmail.com","coloradowaterjet.com contact",$msg);
		mail("jdvm81@gmail.comm","coloradowaterjet.com contact",$msg,"From: $email");
		mail("rfq@coloradowaterjet.com","coloradowaterjet.com contact",$msg,"From: $email");
		//mail("miles@oldtownmedia.com", "coloradowaterjet.com contact",$msg,"From: $email");
		echo "Thank you for submitting the contact form.  We will get in touch with you as soon as possible.";
		include('footer.php');
		exit();
	}
}

?>


<p>Send us an email by using the form below or use our <a href='/quote/index.php'>Request A Quote Form</a>.</p>


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

<form name='contact' method='post' action='/contact.php' onsubmit='return verify(this);'>
<table>
<tr><td>Name:</td><td><input type='text' name='name'/></td></tr>
<tr><td>Email:</td><td><input type='text' name='email'/></td></tr>
<tr><td>Phone Number:</td><td><input type='text' name='phone' onkeyup="inputmask(this.value,this,'phone');" maxlength='12'/></td></tr>
<tr><td>Company:</td><td><input type='text' name='company'/></td></tr>
<tr valign='top'><td>Question/Comment:</td><td><textarea name='comments' cols='30' rows='5'></textarea></td></tr>
<tr><td>&nbsp;</td><td><input type='submit' name='mysubmit' value='Send Message'/></td></tr>
</table>
<input type='hidden' name='action' value=''/>
<input type='hidden' name='accountid' value='<?=$accountrow["accountid"]?>'/>
</form>





<?php include('footer.php'); ?>