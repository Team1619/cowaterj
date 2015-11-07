<?php
	// GET FUNCTIONS AND CLASSES
	require_once("functions.php");
	require_once("classes/ThumbnailImage.php");
	require_once("classes/GoogleMapAPI.class.php");
	require_once("classes/Time.php");
	
	$this_time = new Time();
	
	// GOOGLE KEY
	$googlemaps_key="ABQIAAAACqZxKlQfJatTDYRjKcEGZxRZcrMhKrosrYQYjBcFkq19NSfcQxSuDWk7TMW1qXHRg_ahIhhh46ULKg";
	
	// GET VISITORID
	$visitorid=visitorid_set();
	
	// GET ACCOUNT ROW
	$accountrow=getaccountrow();
	
	// POST/GET VARIABLES
	extract($_POST);
	extract($_GET); 

	$filespecs="Our machines use DXF files. If you create the drawing with a CAD program please send DXF. If the file is created in a vector graphics program please send it as AI or EPS. Files must be PC (IBM) format.";
	$filespecs.="<br><br> ";
	$filespecs.="<b>CAD files</b>";
	$filespecs.="<ul>";
	$filespecs.="<li>Save as DXF (if using AutoCad; Rev 14 or higher preferred)</li>";
	$filespecs.="<li>Create and save drawing at full scale with only features to be cut (no drawing sheet format, no dimensions)(material outline OK)</li>";
	$filespecs.="<li>Draw holes that will be tapped at tap drill size.</li>";
	$filespecs.="<li>Explode all blocks and symbols.</li>";
	$filespecs.="<li>Purge drawing before saving. (In AutoCad: File|Drawing Utilities|Purge)</li>";
	$filespecs.="<li>We prefer to create any nesting. We can usually nest parts at 0.1 inch spacing.</li>";
	$filespecs.="<li>Provide paper drawing or additional CAD file of part with dimensions and tolerances.</li>";
	$filespecs.="</ul>";
	$filespecs.="<b>Artwork</b>";
	$filespecs.="<ul>";
	$filespecs.="<li>Adobe Illustrator Ver 8.0 preferred. Send computer generated artwork as AI or EPS</li>";
	$filespecs.="<li>Vector artwork, not raster/bitmap.</li>";
	$filespecs.="<li>Convert Text to Outlines</li>";
	$filespecs.="<li>Be sure that View|Outline shows all lines as they will be cut.</li>";
	$filespecs.="<li>We can also use ScanVec CASmate .scv files</li>";
	$filespecs.="</ul>";
	$filespecs.="<b>Sketches</b> (faxing or mailing) ";
	$filespecs.="<ul>";
	$filespecs.="<li>Use dark, heavy, single lines, or solid filled image.</li>";
	$filespecs.="<li>Draw a line with cross lines a specified distance apart \"-|---- 5 ----|-\". This is necessary because faxes and copy machines will change the image size.</li>";
	$filespecs.="<li>Maximum size 8.5\" x 11\".</li>";
	$filespecs.="</ul>";
	$filespecs.="<b>Size Limitations</b>";
	$filespecs.="<ul>";
	$filespecs.="<li>File upload is limited to 5 Mb. If you can, compress the file using Winzip (.zip).</li>";
	$filespecs.="<li>For CAD files; purge before saving. For AI files; be sure that Artboard is larger than artwork.</li>";
	$filespecs.="<li>If you cannot reduce the file size to 5 Mb please call us.</li>";
	$filespecs.="</ul>";
	$filespecs.="<b>Accepted File Extentions</b>";
	$filespecs.="<ul>";
	$filespecs.="<li>Only .zip, .ai, .eps., .dxf, .scv, .dwg, and .pdf files will be accepted.</li>";
	$filespecs.="<li>The quote request form will reject all other file types.</li>";
	$filespecs.="<li>If you cannot produce one of the accepted file types please call us.</li>";
	$filespecs.="</ul>";
	
?>