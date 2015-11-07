<?php require_once('header_php.php'); ?>







<?php
if($action=='logout'){
	@mysql_query("DELETE FROM visitors_accounts WHERE visitorid='$visitorid'");
	$accountrow="";
}
?>






<?php include('header.php'); ?>






Colorado WaterJet Company is Colorado's oldest, largest, and most experienced job shop specializing in abrasive waterjet shape cutting.  Our equipment includes a Dynamic WaterJet; the most advanced waterjet available.
<br><br>
Colorado WaterJet Company was established in 1997 by Dan Nibbelink, a Mechanical Engineer and Registered Professional Engineer. At that time he had twenty years experience in mechanical design and manufacturing engineering with medical equipment, computer peripherals and industrial companies. That experience helps him understand your requirements so you can get the best product for the lowest cost.
<br><br>
Dan Nibbelink also is an artist-blacksmith and understands the requirements for architectural projects.
<br><br>
Colorado WaterJet Company is a member of:
<ul>
<li><a href='http://www.wjta.org/' target='_blank'>WJTA</a> - Water Jet Technology Association</li>
<li><a href='http://www.nomma.org/' target='_blank'>NOMMA</a> - National Ornamental and Miscellaneous Metals Association</li>
<li><a href='http://fmametalfab.org/' target='_blank'>FMA</a> - Fabrication and Manufacturers Association</li>
<li><a href='http://www.nfib.com/' target='_blank'>NFIB</a> - National Federation of Independent Businesses</li>
<li><a href='http://www.abana.org/' target='_blank'>ABANA</a> - Artist Blacksmith Association of North America</li>
</ul>





<?php include('footer.php'); ?>