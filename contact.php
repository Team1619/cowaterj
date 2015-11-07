<!--<?php require_once('header_php.php'); ?>-->

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
<tr valign='top'><td rowspan='3'>Colorado WaterJet<br>5186 Longs Peak Road Unit F<br>Berthoud, Colorado 80513</td><td rowspan='3'><img src="/images/clear.gi" height="1" width="60"></td>
<td>970-532-5404</td></tr>
<tr><td><a href="mailto:sales@coloradowaterjet.com">sales@coloradowaterjet.com</a></td></tr>
<!--<tr><td>Fax:</td><td>970-532-5405</td></tr>-->
</table>&nbsp;<br>



<div align="center"><?php $map->printMap(); ?></div>

<?php include('footer.php'); ?>