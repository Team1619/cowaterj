<?php require_once('../header_php.php'); ?>
<?php include('../header.php'); ?>












<?php
// GET PREVIOUS
$sql="SELECT photoid FROM photos_categories WHERE categoryid='$categoryid' AND photoid<'$photoid' ORDER BY photoid DESC LIMIT 1";
$row=mysql_fetch_array(mysql_unbuffered_query($sql));
$previous=$row["photoid"];
// GET NEXT
$sql="SELECT photoid FROM photos_categories WHERE categoryid='$categoryid' AND photoid>'$photoid' ORDER BY photoid ASC LIMIT 1";
$row=mysql_fetch_array(mysql_unbuffered_query($sql));
$next=$row["photoid"];


$row=mysql_fetch_array(mysql_unbuffered_query("SELECT * FROM photos WHERE photoid='$photoid'"));



echo "<table width=\"450\" align=\"center\">";
echo "<tr><td colspan='3' align=\"center\"><img src=\"/images/getimage.php?path=".$_SERVER['DOCUMENT_ROOT']."/gallery/gallery_".$row["photoid"].".jpg&amp;maxsize=450\"/></td></tr>";
echo "<tr><td align='left' width=\"33%\">";
if($previous){echo "<a href='/gallery/photo.php?photoid=$previous&categoryid=$categoryid'>";}
echo "&lt;&lt; previous";
if($previous){echo "</a>";}
echo "</td><td align=\"center\" width=\"34%\">";
// GET GALLERY NAME
echo "<a href=\"/gallery/category.php?categoryid=$categoryid\">$pagetitle</a><br><a href=\"/gallery/index.php\">Main Gallery</a><br>";
echo "</td><td align='right' width=\"33%\">";
if($next){echo "<a href='/gallery/photo.php?photoid=$next&categoryid=$categoryid'>";}
echo "next &gt;&gt;";
if($next){echo "</a>";}
echo "</td></tr>";
echo "<tr><td colspan=\"3\">".$row["caption"]."</td></tr>";
echo "</table>";
?>














<?php include('../footer.php'); ?>