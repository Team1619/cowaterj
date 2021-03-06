<?php require_once('header_php.php'); ?>

<?php
	/////////////////////////////////////////////////////
	// DETERMINE PAGE TITLE
	$pagetitle="";
	

	if(strlike($_SERVER["PHP_SELF"],"admin/index.php")){$pagetitle.="Admin";}
	elseif(strlike($_SERVER["PHP_SELF"],"admin/quotes.php")){$pagetitle.="Admin - Manage Quotes";}
	elseif(strlike($_SERVER["PHP_SELF"],"admin/quote.php")){$pagetitle.="Admin - Quote Details";}
	elseif(strlike($_SERVER["PHP_SELF"],"admin/accounts.php")){$pagetitle.="Admin - Manage Accounts";}
	elseif(strlike($_SERVER["PHP_SELF"],"admin/account.php")){$pagetitle.="Admin - Account Details";}
	elseif(strlike($_SERVER["PHP_SELF"],"admin/photos.php")){$pagetitle.="Admin - Manage Gallery Photos";}
	elseif(strlike($_SERVER["PHP_SELF"],"admin/photos_edit.php")){$pagetitle.="Admin - Edit Gallery Photo";}
	elseif(strlike($_SERVER["PHP_SELF"],"admin/photos_categories.php")){$pagetitle.="Admin - Manage Gallery Categories";}
	elseif(strlike($_SERVER["PHP_SELF"],"admin/photos_categories_edit.php")){$pagetitle.="Admin - Edit Gallery Category";}
	
	elseif(strlike($_SERVER["PHP_SELF"],"contact.php")){$pagetitle.="Contact Us";}
	elseif(strlike($_SERVER["PHP_SELF"],"filespecifications.php")){$pagetitle.="Design File Specifications";}
	elseif(strlike($_SERVER["PHP_SELF"],"capabilities.php")){$pagetitle.="Capabilities";}
	elseif(strlike($_SERVER["PHP_SELF"],"applications.php")){$pagetitle.="Ideal Applications";}
	elseif(strlike($_SERVER["PHP_SELF"],"why.php")){$pagetitle.="Why Choose Colorado Water Jet?";}
	elseif(strlike($_SERVER["PHP_SELF"],"quote/")){$pagetitle.="Request A Quote";}
	elseif(strlike($_SERVER["PHP_SELF"],"gallery/")){
		$pagetitle.="Gallery";
		// FIGURE OUT GALLERY CATEGORY AND PUT IN IN TITLE
		$row=mysql_fetch_array(mysql_unbuffered_query("SELECT categoryname FROM photocategories WHERE categoryid=\"$categoryid\" LIMIT 1"));
		if($row["categoryname"]){$pagetitle=$row[categoryname]." $pagetitle";}
	}
	elseif(strlike($_SERVER["PHP_SELF"],"account/index.php")){$pagetitle.="Account Information";}
	elseif(strlike($_SERVER["PHP_SELF"],"account/password.php")){$pagetitle.="Password Reminder";}
	elseif(strlike($_SERVER["PHP_SELF"],"account/quote.php")){$pagetitle.="Quote Receipt";}
	elseif(strlike($_SERVER["PHP_SELF"],"account/create.php")){$pagetitle.="Create An Account";}
	
	else{$pagetitle.="Colorado Waterjet Company";}
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<?php
	echo "<title>$pagetitle";
	if($pagetitle){echo " :: ";}
	echo "Colorado Water Jet Company</title>";
?>
<LINK REL=stylesheet HREF="/style.css" TYPE="text/css" MEDIA=screen>
<script type="text/javascript">

	/////////////////////////////////////////////////////////////////////////
	// FORM FUNCTIONS

	function trim(strText){
		// this will get rid of leading spaces
		while (strText.substring(0,1)==' '){strText = strText.substring(1,strText.length);}
		// this will get rid of trailing spaces
		while (strText.substring(strText.length-1,strText.length)==' '){strText=strText.substring(0,strText.length-1);}
		return strText;
	} 
	
	function validemail(input){
		var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (filter.test(input)){return(true);}
		else{return(false);}
	}
	
	function validurl(input){
		var j=new RegExp();
		j.compile("^[A-Za-z]+://[A-Za-z0-9-]+\.[A-Za-z0-9]+"); 
		if(!j.test(input)){return(false);}
		return(true);
	}
	
	function inputmask(input,textbox,what){
		i=0;
		while(i<input.length){
			if(what=='alpha'){
				//i++;
				//if(isValid(input.charAt(i),sep+lwr+upr)){i++;}
				if((input.charAt(i)>='A' && input.charAt(i)<='Z') || (input.charAt(i)>='a' && input.charAt(i)<='z') || input.charAt(i)==' ' || input.charAt(i)=='.' || input.charAt(i)=="'" || input.charAt(i)=='-' || input.charAt(i)==','){i++;}
				else{input=input.substring(0,i)+input.substring(i+1,input.length);}			
			}
			else{
				if(isFinite(input.charAt(i)) && input.charAt(i)!=" "){i++;}
				else{input=input.substring(0,i)+input.substring(i+1,input.length);}
			}
		}
		if(input.length>3 && what=='phone'){input=input.substring(0,3)+"-"+input.substring(3,input.length);}
		if(input.length>7 && what=='phone'){input=input.substring(0,7)+"-"+input.substring(7,input.length);}
		textbox.value=input;
	}
	
</script>
<?php
if(strlike($_SERVER["PHP_SELF"],"contact.php")){
	//$map->printHeaderJS();
    //$map->printMapJS();
}
?>
</head>
<?php
echo "<body bgcolor=\"#FFFFFF\"";
//if(strlike($_SERVER["PHP_SELF"],"contact.php")){echo " onload=\"onLoad();\"";}
echo " leftmargin=\"0\" rightmargin=\"0\" topmargin=\"0\" bottommargin=\"0\">";
?>






<table width="882" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tr valign="bottom"><td colspan="2" width="882" background="/images/bluestripes.gif"><img src="/images/title_main.gif"></td></tr>
<tr valign="top" height="100%"><td class="gradient_left" width="328">
	<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr valign="top"><td width="100%">
<img src="/images/logo_bottom.gif"><br>
<img src="/images/clear.gif" height="1" width="116">
<div class="mynav">

<?php
if(strlike($_SERVER["PHP_SELF"],"admin")){
	echo "Admin";
	echo "<ul>";
	echo "<li><a href='/admin/quotes.php' class=\"nav\">Quotes</a></li>";
	echo "<li><a href='/admin/photos.php' class=\"nav\">Photos</a></li>";
	echo "<li><a href='/admin/photos_categories.php' class=\"nav\">Photo Categories</a></li>";
	echo "</ul>";
	echo "<br><br><br><br>";
}
?>
<a href='/index.php' class="nav">Home</a>
<br><br>
<a href='/capabilities.php' class="nav">Capabilities</a>
<br><br>
<a href='/applications.php' class="nav">Ideal<br>Applications</a>
<br><br>
<a href='/why.php' class="nav">Why<br>Water Jet?</a>
<br><br>
<a href='/quote/index.php' class="nav">Request A<br>Quote</a>
<br><br>
<a href='/filespecifications.php' class="nav">Design File<br>Specifications</a>
<br><br>
<a href='/contact.php' class="nav">Contact Us</a>
<br><br>
<a href='/gallery/index.php' class="nav">Gallery</a>

<?php
	/*
	$result=mysql_unbuffered_query("SELECT photocategories.categoryid, photocategories.categoryname, count(photos_categories.photoid) AS 'num' FROM photocategories, photos_categories WHERE photocategories.categoryid=photos_categories.categoryid GROUP BY photocategories.categoryid ORDER BY photocategories.categoryname");
	echo "<ul>";
	while($row=mysql_fetch_array($result)){
		echo "<li><a href=\"/gallery/category.php?categoryid=".$row["categoryid"]."\" class=\"nav\">".$row["categoryname"]."</a></li>";
	}
	echo "</ul>";
	*/
?>
</div>



</td><td><img src="/images/wheel3.jpg"></td></tr></table>

</td><td width="554" class="gradient_right">







<table cellpadding="0" cellspacing="0" border="0"><tr valign="top"><td width="385"><img src="/images/title_shadow.gif"></td><td width="169" class="login"><div class="loginbutton"><b>
<?php
	echo "<a href='/account/index.php' class='loginlink'>";
	if($accountrow){
		echo "<a href='/account/index.php' class='loginlink'>my account</a>";
		echo " | ";
		echo "<a href='/index.php?action=logout' class='loginlink'>log out</a>";
	}
	else{
		echo "<a href='/account/create.php' class='loginlink'>create account</a>";
		echo " | ";
		echo "<a href='/account/index.php' class='loginlink'>log in</a>";
	}

?>
</b></div></td></tr></table>


<table width="100%" height="100%" cellpadding="15" cellspacing="0" border="0"><tr valign="top" height="100%"><td>













<div class='pagetitle'><?=$pagetitle?>&nbsp;</div>