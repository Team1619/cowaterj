<?php require_once('../header_php.php'); ?>
<?php include('../header.php'); ?>







<?php
// CATEGORY DESCRIPTION
$row=mysql_fetch_array(mysql_unbuffered_query("SELECT categorydescription FROM photocategories WHERE categoryid='$categoryid'"));
if($row["categorydescription"]){echo $row["categorydescription"]."<br><br>";}
?>

<a href="/gallery/index.php">&lt;&lt; Main Gallery</a><br><br>


<?php

$count=0;
$maxcols=3;
$sql="SELECT photos_categories.photoid, photos.caption FROM photos_categories";
$sql.=" INNER JOIN photos ON photos.photoid=photos_categories.photoid";
$sql.=" WHERE photos_categories.categoryid='$categoryid'";
$sql.=" GROUP BY photos_categories.photoid";
$sql.=" ORDER BY photos_categories.photoid";

$result=mysql_unbuffered_query($sql);
echo "<table>";
while($row=mysql_fetch_array($result)){

	if(fmod($count,$maxcols)==0){
		if($count>0){echo "</tr>";}
		echo "<tr>";
	}
	echo "<td align='center'><a href='/gallery/photo.php?photoid=".$row["photoid"]."&categoryid=$categoryid'><img src='/gallery/gallery_".$row["photoid"].".jpg' width='150px'></a></td>";
	$count++;	
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