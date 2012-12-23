<?php
include_once("clsImage.php");
//
// class: clsPattern
// extends: clsImage
// description: handles the pattern image
//
// author: sophia daniels
// website: www.catgirlgames.com
// email: sophia@sophiadaniels.com
//
class clsPattern extends clsImage {
	// public vars
	var $colors;
	var $speckle_colors;
	var $speckle_count = 500;
	var $speckle_min = 5;
	var $speckle_max = 25;
	// constructor
	function clsPattern(){
		$this->colors = new clsColors();
		$this->speckle_colors = new clsColors();
//		$this->load($path);
	}
	//
	// this is the public function to make a first column thing....
	//
	// options[method] = string						RandomDot || TiledImage
	// options[speckle] = array						(optional)
	// options[speckle][colors] = array of colors  	(optional)
	// options[speckle][color_count] = int  		(optional)
	// options[colors] = array of colors  			(optional)
	// options[color_count] = int			  		(optional)
	function firstColumn($w,$h){
		// ok now which methods to use?
		if($this->loaded){
			$im = $this->makeTiledColumn($w,$h);
		} else {
			if(isset($this->colors) && $this->colors->count() > 0){
				$im = $this->makeRandomDotColumn($w,$h,$this->colors);
			} else {
				$this->colors->addColors(rand(4,8),128,128,128,0,128);
				$im = $this->makeRandomDotColumn($w,$h,$this->colors);
			}
		}
		if(isset($this->speckle_colors) && $this->speckle_colors->count() > 0){
			$im = $this->addSpecks($im,$w,$h,$this->speckle_colors);
		}
		return $im;
	}
	//
	// make new column
	//
	private function makeColumn($w,$h,$r=0,$b=0,$g=0){
		// create the blank column
		$im = imagecreatetruecolor($w,$h);
		// scale the pattern to fit.
		if($this->loaded)
			$this->scale($w,$h);
		// set the background color for the column
		$color = imagecolorallocate($im,$r,$g,$b);
		imagefill($im,0,0,$color);
		return $im;
	}
	//
	// make and return a complete first column of tiled pattern
	//
	private function makeTiledColumn($w,$h){
		// make a new column
		$im = $this->makeColumn($w,$h);
		// now tile the pattern image ($this->im) onto the new image ($im)
		for($y = 0; $y < $h; $y += $this->height){
			imagecopy($im, $this->im,0,$y,0,0,$this->width,$this->height);
		}
		// return the column.
		return $im;
	}
	//
	// make and return a complete first column of tiled pattern
	//
	private function makeRandomDotColumn($w,$h,$colors){
		$c = $colors->count();
		// make a new column
		$im = $this->makeColumn($w,$h);
		// now allocate colors
		/*
		for($i = 0; $i < $c; $i++){
			$color = imagecolorallocate($im,$colors[$i]['red'],$colors[$i]['green'],$colors[$i]['blue']);
			$colors[$i]['index'] = $color;
		}
		*/
		$colors->allocateColors($im);
		// now tile the pattern image ($this->im) onto the new image ($im)
		for($y = 0; $y < $h; $y++){
			for($x = 0; $x < $w; $x++){
				$color = $colors[rand(0,$c-1)]['index'];
				imageellipse($im,$x,$y,1,1,$color);
			}
		}
		return $im;
	}
	private function addSpecks($im,$w,$h,$colors){
		$c = $colors->count();
		$colors->allocateColors($im);
		for($i = 0; $i < $this->speckle_count; $i++){
			$color = $colors[rand(0,$c-1)]['index'];
			imagefilledellipse($im,rand(0,$w),rand(0,$h),rand($this->speckle_min,$this->speckle_max),rand($this->speckle_min,$this->speckle_max),$color);
		}
		// return the column.
		return $im;
	}
}
//
// class: clsColors
// extends: ArrayObject
// description: an array of colors. with color generation functions.
//
// author: sophia daniels
// website: www.catgirlgames.com
// email: sophia@sophiadaniels.com
//
class clsColors extends ArrayObject {
	// add color random
	public function addColor($red,$green,$blue,$alpha = 0,$random = 0, $random_alpha = 0){
		// create the color
		$color = array();												// create color array
		$color['red'] = rand($red-$random,$red+$random);				// create randomized red
		$color['green'] = rand($green-$random,$green+$random);			// create randomized green
		$color['blue'] = rand($blue-$random,$blue+$random);				// create randomized blue
		$color['alpha'] = rand($alpha-$random_alpha,$alpha+$random_alpha);			// create randomized alpha
		$color['index'] = -1;
		// make sure it's valid
		if($color['red'] < 0){
			$color['red'] = 0;
		}
		if($color['red'] > 255){
			$color['red'] = 255;
		}

		if($color['green'] < 0){
			$color['green'] = 0;
		}
		if($color['green'] > 255){
			$color['green'] = 255;
		}
		
		if($color['blue'] < 0){
			$color['blue'] = 0;
		}
		if($color['blue'] > 255){
			$color['blue'] = 255;
		}
		
		if($color['alpha'] < 0){
			$color['alpha'] = 0;
		}
		if($color['alpha'] > 110){
			$color['alpha'] = 110;
		}
		$this->append($color);
	}
	// allocate colors
	public function allocateColors($im){
		foreach($this as $i => $color){
			$color['index'] = imagecolorallocatealpha($im,$color['red'],$color['green'],$color['blue'],$color['alpha']);
			$this->offsetSet($i,$color);
		}
	}
	// add colors
	public function addColors($count,$red,$green,$blue,$alpha = 0,$random = 0, $random_alpha = 0){
		for($i = 0; $i < $count; $i++){
			$this->addColor($red,$green,$blue,$alpha,$random,$random_alpha);
		}
	}
	
	// this should allow us to use this class like an array. whenever needed.... hopefully....
	public function append($value) { 
		$args = func_get_args();
		return call_user_func_array(array(parent, __FUNCTION__), $args); 
	} 
	public function offsetGet($name) { 
		$args = func_get_args();
		return call_user_func_array(array(parent, __FUNCTION__), $args); 
	} 
	public function offsetSet($name, $value) { 
		$args = func_get_args();
		return call_user_func_array(array(parent, __FUNCTION__), $args); 
	} 
	public function offsetExists($name) { 
		$args = func_get_args();
		return call_user_func_array(array(parent, __FUNCTION__), $args); 
	} 
	public function offsetUnset($name) { 
		$args = func_get_args();
		return call_user_func_array(array(parent, __FUNCTION__), $args); 
	} 	
}
?>