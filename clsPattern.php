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
	function clsPattern($path){
		$this->load($path);
	}
	//
	// make new column
	//
	function makeColumn($w,$h){
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
	function makeTiledColumn($w,$h){
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
	function randomColors($c){
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
	function makeRandomDotColumn($w,$h,$colors){
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
		// return the column.
		return $im;
	}
}
?>