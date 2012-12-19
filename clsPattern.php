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
	// private vars
	// constructor
	function clsPattern(){
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
	function firstColumn($w,$h,$options){
		// what pattern method are we using?
		$method = "RandomDot"; // RandomDot // TiledImage // ??
		if($this->loaded){
			$method = "TiledImage";
		} elseif(isset($options['method'])){
			$method = $options['method'];
		}
		if(isset($options['colors'])){
			// ok grab out the spekle colors
			$colors = $options['colors'];
		} elseif(isset($options['speckle']['color_count'])){
			// ok grab out the spekle colors
			$colors = $this->randomColors($options['color_count']);
		} else
			$colors = $this->randomColors(rand(3,10));
		// add speckle effect?
		$speckle = false;
		if(isset($options['speckle'])){
			$speckle = true;
			if(isset($options['speckle']['colors'])){
				// ok grab out the spekle colors
				$spekle_colors = $options['speckle']['colors'];
			} elseif(isset($options['speckle']['color_count'])){
				// ok grab out the spekle colors
				$spekle_colors = $this->randomColors($options['speckle']['color_count']);
			} else{
				//$spekle_colors = $colors;
			}
		}
		// ok now which methods to use?
		switch($method){
			case "TiledImage":
			case "Tiled":
			case "Image":
				$im = $this->makeTiledColumn($w,$h);
				break;
			default:
				$im = $this->makeRandomDotColumn($w,$h,$colors);
				$im = $this->addSpecks($im,$w,$h,$colors);
				break;
		}
		if($speckle){
			$im = $this->addSpecks($im,$w,$h,$spekle_colors);
		}
		return $im;
	}
	//
	// make new column
	//
	private function makeColumn($w,$h){
		// create the blank column
		$im = imagecreatetruecolor($w,$h);
		// scale the pattern to fit.
		if($this->loaded)
			$this->scale($w,$h);
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
	// make colors
	private function randomColors($c){
		$colors = array();
		for($i = 0; $i < $c; $i++){
			$color = array();
			$color['r'] = rand(0,255);
			$color['g'] = rand(0,255);
			$color['b'] = rand(0,255);
			$colors[$i] = $color;
		}
		return $colors;
	}
	//
	// make and return a complete first column of tiled pattern
	//
	private function makeRandomDotColumn($w,$h,$colors){
		$c = count($colors);
		// make a new column
		$im = $this->makeColumn($w,$h);
		// now allocate colors
		for($i = 0; $i < $c; $i++){
			$colors[$i] = imagecolorallocate($im,$colors[$i]['r'],$colors[$i]['g'],$colors[$i]['b']);
		}
		// now tile the pattern image ($this->im) onto the new image ($im)
		for($y = 0; $y < $h; $y++){
			for($x = 0; $x < $w; $x++){
				$color = $colors[rand(0,$c-1)];
				imageellipse($im,$x,$y,1,1,$color);
			}
		}
		return $im;
	}
	private function addSpecks($im,$w,$h,$colors){
		$c = count($colors);
		// now allocate colors
		for($i = 0; $i < $c; $i++){
			$colors[$i] = imagecolorallocate($im,$colors[$i]['r'],$colors[$i]['g'],$colors[$i]['b']);
		}
		for($i = 0; $i < $c*5; $i++){
			$color = $colors[rand(0,$c-1)];
			imagefilledellipse($im,rand(0,$w),rand(0,$h),rand(5,10),rand(5,10),$color);
		}
		// return the column.
		return $im;
	}
}
?>