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

// FIRST FIGURE OUT DESIGN FILE(S)
for($i=1;$i<=10;$i++){
	$design_file="design_file_$i";
	//echo "$design_file<hr>";
	if(is_uploaded_file($_FILES[$design_file]['tmp_name'])){
		// INSERT NEW ROW IN DESIGN FILES TABLE
		@mysql_query("INSERT INTO design_files (quoteid,name,ext,type) VALUES (\"$quoteid\",\"".$_FILES[$design_file]['name']."\",\"".substr($_FILES[$design_file]['name'],strrpos($_FILES[$design_file]['name'],"."))."\",\"".$_FILES[$design_file]['type']."\")");
		$fileid=mysql_insert_id();
		//echo "$fileid<hr>";
		$design_file_name="file_$fileid".substr($_FILES[$design_file]['name'],strrpos($_FILES[$design_file]['name'],"."));
		//echo "$design_file_name<hr>";
		@move_uploaded_file($_FILES[$design_file]['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/admin/designfiles/$design_file_name");
	}
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
//echo $sql;
@mysql_query("UPDATE quotes SET timestamp=\"".mytime()."\", status=\"new\", visitorid=\"0\" WHERE quoteid=\"$quoteid\"");
//mail("andy@claypotconsulting.com","coloradowaterjet.com quote","A quote request has been submitted from coloradowaterjet.com","From: quotes@coloradowaterjet.com");
// alan@coloradowaterjet.com redhawk800@gmail.com ,sales@coloradowaterjet.com
mail("sales@coloradowaterjet.com","coloradowaterjet.com quote","A quote request has been submitted from coloradowaterjet.com","From: quotes@coloradowaterjet.com");
//mail("julia@claypotcreative.com","coloradowaterjet.com quote","A quote request has been submitted from coloradowaterjet.com","From: quotes@coloradowaterjet.com");


include('../header.php');

// OUTPUT RECIEPT

echo "<p>Thank you for submitting a quote request - your request has been sent.  Please print a copy of this receipt for your records.</p>";


echo receipt($quoteid);

include('../footer.php');
?>