<?php
require_once('../header_php.php');

if(!isset($maxsize)){$maxsize=100;}
if(isset($path)){
  $thumb = new ThumbNailImage($path, $maxsize);		
  $thumb->getImage();
}
?>
