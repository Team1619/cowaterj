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


<p>If this is your first time requesting a quote from us, please take a moment to read our design files specifications below to help lower your project costs.</p>
<p>Otherwise, please email us directly at <a href="mailto:Sales@ColoradoWaterJet.com?Subject=Quote" target="_top">Sales@ColoradoWaterJet.com</a>.</p>
<p>Please note a minimum order cost of $100 USD and we are prohibited from using any trademarked images.</p>
<h2>Design File Preferences:</h2>
<p>The machines at Colorado Waterjet use .dxf files to lead the cutting process accurately.</p>
<p>If your file was created in a <b>CAD Program</b>, please send as a .dxf file type.</p>
<ul>
<li>Create and save the drawing at full scale with no drawing sheet format or dimensions.</li>
<li>Save all entities on 1 layer and, if possible, save as a 2004 .dxf or earlier.</li>
<li>Draw tapped holes at tap drill size.</li>
<li>Explode all blocks and symbols.</li>
<li>Purge drawing before saving. (File|Drawing Utilities|Purge)</li>
<li>We prefer to create any nesting for the customer, but if your personal nesting is necessary, a minimum 0.2 inch spacing is recommended.</li>
<li>Please provide an additional PDF or CAD file with dimensions and tolerances.</li>
</ul>

<p>If your file was created in a <b>vector graphics program</b>, please send files to us as AI, SCV or EPS format.</p>
<ul>
<li>Please do not send us your file as a raster or bitmap.</li>
<li>Convert text to outlines.</li>
<li>Ensure that (View|Outline) shows all lines as they will be cut.</li>
</ul>

<p>If your design is only a <b>sketch:</b></p>
<ul>
<li>Please use dark, heavy, single lines or solid filled images.</li>
<li>Sketches must be at most 8.5"x11"</li>
</ul>

<p>All files must be usable by Windows OS and less than 25 Mb. If possible, files can be compressed further using <a href="http://www.7-zip.org/download.html">7zip.</a></p>

<p>Other acceptable formats: .dwg, .stp and .pdf.</p>
<p>Please call us if you have any questions: 970-532-5404</p>


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