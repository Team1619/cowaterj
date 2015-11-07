<?php

class Time{
	private $mytime;
	private $adjust;
////////////////////////////////////////////////////////
//constructor
////////////////////////////////////////////////////////
	public function __construct($mytime=0){
		date_default_timezone_set('America/Denver');
		$this->setValue($mytime);
	}
////////////////////////////////////////////////////////
// destructor
////////////////////////////////////////////////////////
	public function __destruct(){
		//$this->close();
	}
////////////////////////////////////////////////////////
// public methods
////////////////////////////////////////////////////////
	public function getValue(){
		return($this->mytime);
	}
	public function getTime(){
		return($this->mytime);
	}
	public function updateTime(){
		return($this->mytime);
	}
	public function getFormatted($format="m/d/Y H:i:s"){
		return(date($format,$this->getValue()));
	}
	public function setValue($value=0){
		$this->setTime($value);
	}
	public function setTime($value=0){
		if($value){$this->mytime=$value;}
		else{$this->mytime=time();}
	}
	public function getHours(){
		return($this->getValue()/(60*60));
	}
	public function setZero(){
		$this->mytime=0;
	}
	public function addTime($addition){
		$this->setValue($this->getValue()+$addition);
	}
	public function UTC2PT(){
		if($this->getFormatted('I')){
			$this->setValue($this->getTime()-7*60*60);
		}
		else{
			$this->setValue($this->getTime()-8*60*60);
		}
	}
	public function UTC2MT(){
		if($this->getFormatted('I')){
			$this->setValue($this->getTime()-6*60*60);
		}
		else{
			$this->setValue($this->getTime()-7*60*60);
		}
	}
	public function UTC2ET(){
		if($this->getFormatted('I')){
			$this->setValue($this->getTime()-4*60*60);
		}
		else{
			$this->setValue($this->getTime()-5*60*60);
		}
	}
	public function UTC2CT(){
		if($this->getFormatted('I')){
			$this->setValue($this->getTime()-5*60*60);
		}
		else{
			$this->setValue($this->getTime()-6*60*60);
		}
	}
	public function PT2UTC(){
		if($this->getFormatted('I')){
			$this->setValue($this->getTime()+7*60*60);
		}
		else{
			$this->setValue($this->getTime()+8*60*60);
		}
	}
	public function MT2UTC(){
		if($this->getFormatted('I')){
			$this->setValue($this->getTime()+6*60*60);
		}
		else{
			$this->setValue($this->getTime()+7*60*60);
		}
	}
	public function ET2UTC(){
		if($this->getFormatted('I')){
			$this->setValue($this->getTime()+4*60*60);
		}
		else{
			$this->setValue($this->getTime()+5*60*60);
		}
	}
	public function CT2UTC(){
		if($this->getFormatted('I')){
			$this->setValue($this->getTime()+5*60*60);
		}
		else{
			$this->setValue($this->getTime()+6*60*60);
		}
	}
	public function CT2MT(){
		$this->setValue($this->getTime()-60*60);
	}
	public function ET2CT(){
		$this->ET2UTC();
		$this->UTC2CT();
	}
	public function ET2MT(){
		$this->ET2UTC();
		$this->UTC2MT();
	}
	public function MT2ET(){
		$this->MT2UTC();
		$this->UTC2ET();
	}
////////////////////////////////////////////////////////
//private methods
////////////////////////////////////////////////////////


}//end class
////////////////////////////////////////////////////////
?>
