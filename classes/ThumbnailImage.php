<?php
//requires GD 2.0.1 or higher
class ThumbnailImage{
	//private data mambers
	var $image;
	var $maxdimension;
	//not applicable to gif or png
	var $quality=100;
	var $fileextension;
	var $mimetype;
	var $imageproperties;
	var $types= array("jpg", "jpeg", "gif", "png");
////////////////////////////////////////////////////////
//constructor
////////////////////////////////////////////////////////
	function ThumbnailImage($path, $maxdimension=100){
		$this->maxdimension=$maxdimension;
		//check path
		is_file($path) or die ("File: $path doesn't exist.");
		//check type	
		$extension=substr($path,(strpos($path, ".")+1));
		$extension= strtolower($extension);
		in_array($extension, $this->types) or die ("Incorrect file type.");
		$this->fileextension=$extension;
		$this->setMimeType($extension);
		//get dimensions by creating imageproperties
		$this->imageproperties = GetImageSize($path);		
		//create image
		if($extension=="jpeg" || $extension=="jpg"){
			$this->image=imagecreatefromJPEG($path);			
		}elseif($extension=="gif"){
			$this->image=imagecreatefromGIF($path);
		}elseif($extension=="png"){
			$this->image=imagecreatefromPNG($path);
		}else{
			die("Couldn't create image.");
		}
		$this->createThumb();
	}
////////////////////////////////////////////////////////
//public methods
////////////////////////////////////////////////////////
	function getImage(){
		if(!headers_sent()){header("Content-type: $this->mimetype");}
		if($this->fileextension=="jpeg" || $this->fileextension=="jpg"){
			imagejpeg($this->image,"",$this->quality);			
		}elseif($this->fileextension=="gif"){
			$image=imagegif($this->image);
		}elseif($this->fileextension=="png"){
			$image=imagepng($this->image);
		}else{
			die("Couldn't create image.");
		}
	}
////////////////////////////////////////////////////////
	function getMimeType(){
		return $this->mimetype;
	}
////////////////////////////////////////////////////////
	function getQuality(){
		$quality;
		if($this->fileextension=="jpeg" || $this->fileextension=="jpg"){
			$quality=$this->quality;
		}else{
			$quality=-1;
		}
		return quality;
	}
////////////////////////////////////////////////////////
	function setQuality($quality){
		if($quality > 100 || $quality < 1)
			$quality=75;
		if($this->mimetype=="image/jpeg"){
			$this->quality=$quality;
		}
	}
////////////////////////////////////////////////////////
	function destroy(){
		imagedestroy($this->image);
	}
////////////////////////////////////////////////////////
//private methods
////////////////////////////////////////////////////////
	function setMimeType($extension){
		if($extension=="jpeg" || $extension=="jpg"){
			$this->mimetype="image/jpeg";
		}elseif($extension=="png"){
			$this->mimetype="image/png";
		}elseif($extension=="gif"){
			$this->mimetype="image/gif";
		}else{
			die ("Not a recognized type.");
		}
	}
////////////////////////////////////////////////////////
	function createThumb(){
		$srcW=$this->imageproperties[0];
		$srcH=$this->imageproperties[1];
		//only adjust if larger than max
		if($srcW>$this->maxdimension || $srcH>$this->maxdimension){
			$reduction=$this->calculateReduction($srcW,$srcH);
			//get proportions
  		$desW=round($srcW/$reduction);
  		$desH=round($srcH/$reduction);
			//check for gif
			//create copy from original
			if($this->mimetype=="image/gif"){
				$copy=imagecreate($desW, $desH);
			}else{
				$copy=imagecreatetruecolor($desW, $desH);
			}
			imagecopyresampled($copy,$this->image,0,0,0,0,$desW, $desH, $srcW, $srcH)
				 or die ("Image copy failed.");
			//destroy original
			imagedestroy($this->image);
			$this->image=$copy;			
		}
	}
////////////////////////////////////////////////////////
	function calculateReduction($srcW, $srcH){
		//adjust
  	if($srcW<$srcH){
  		$reduction=$srcH/$this->maxdimension;
  	}else{  			
  		$reduction=$srcW/$this->maxdimension;
  	}
		return $reduction;
	}
}//end class
////////////////////////////////////////////////////////
?>
