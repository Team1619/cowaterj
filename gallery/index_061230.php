<?php require_once('../header_php.php'); ?>
<?php include('../header.php'); ?>












<?php
$sql="SELECT photocategories.categoryid, photocategories.categoryname, photocategories.categorydescription, photos_categories.photoid";
$sql.=" FROM photocategories";
$sql.=" INNER JOIN photos_categories ON photocategories.categoryid=photos_categories.categoryid";
$sql.=" ORDER BY photocategories.categoryname, RAND()";

$categoryid="";
$count=0;
$maxcols=3;

$result=mysql_unbuffered_query($sql);
echo "<table>";
while($row=mysql_fetch_array($result)){
	if($categoryid!=$row["categoryid"]){
		$categoryid=$row["categoryid"];
		if(fmod($count,$maxcols)==0){
			if($count>0){echo "</tr>";}
			echo "<tr>";
		}
		echo "<td align='center'>";
		echo "<a href='/gallery/category.php?categoryid=".$row["categoryid"]."'><img src=\"/images/getimage.php?path=".$_SERVER['DOCUMENT_ROOT']."/gallery/gallery_".$row["photoid"].".jpg&amp;maxsize=150\" alt=\"".$row["categoryname"]."\"/>";
	
		//echo "<a href='/gallery/category.php?categoryid=".$row["categoryid"]."'><img src=\"/gallery/gallery_".$row["photoid"]."_th.jpg\"/ alt=\"".$row["categoryname"]."\">";
		
		echo "<br>".$row["categoryname"]."</a></td>";
		$count++;	
	}
}
if($count<$maxcols){echo "</tr>";}
else{
	while(fmod($count,$maxcols)!=0){
		$count++;
		echo "<td>&nbsp;</td>";
	}
	echo "</tr>";	
}
echo "</table>";
?>














<?php include('../footer.php'); ?>