<?php 


require_once('../header_php.php');


if($action!="quote2"){
	echo "<html><body><script>window.location=\"/quote/index.php\";</script>error</body></html>";
	exit();
}
if(!mysql_fetch_array(mysql_unbuffered_query("SELECT quoteid FROM quotes WHERE visitorid='$visitorid'"))){
	echo "<html><body>";
	// REDIRECT TO RECEIPT PAGE
	echo "<script>window.location=\"/account/quote.php?quoteid=$quoteid\";</script>";
	echo "error</body></html>";
	exit();
}






///////////////////////////////////////////////////////////////
// UPDATE QUOTE FORM

// FIRST FIGURE OUT DESIGN FILE
if(!is_uploaded_file($_FILES['design_file']['tmp_name'])){$design_file="";}
else{
	// FIGURE OUT FILE EXTENTION
	$design_file_name="designfile_$quoteid".substr($_FILES['design_file']['name'],strrpos($_FILES['design_file']['name'],"."));
	@move_uploaded_file($_FILES['design_file']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/admin/designfiles/$design_file_name");
	$design_file=$design_file_name;
}


// CREATE UPDATE QUERY
$sql="UPDATE quotes SET ";
$junk=mysql_fetch_assoc(mysql_unbuffered_query("SELECT * FROM quotes WHERE visitorid='$visitorid'"));
$fieldnames=array_keys($junk);
for($i=0;$i<count($fieldnames);$i++){
	if($i>0){$sql.=", ";}
	eval("\$sql.=".$fieldnames[$i].".\"=\\\"\$".$fieldnames[$i]."\\\"\";");
}
$sql.=" WHERE quoteid=\"$quoteid\"";
@mysql_query($sql);
@mysql_query("UPDATE quotes SET timestamp=\"".mytime()."\", status=\"new\", visitorid=\"0\" WHERE quoteid=\"$quoteid\"");
//mail("andy@claypotconsulting.com","coloradowaterjet.com quote","A quote requerst has been submitted from coloradowaterjet.com","From: quotes@coloradowaterjet.com");
mail("rfq@coloradowaterjet.com","coloradowaterjet.com quote","A quote requerst has been submitted from coloradowaterjet.com","From: quotes@coloradowaterjet.com");





include('../header.php');

// OUTPUT RECIEPT

echo receipt($quoteid);

include('../footer.php');
?>