<?php
include_once("clsImage.php");
include_once("clsPattern.php");
include_once("clsDepthMap.php");
//
// class: clsStereogram
// extends: clsImage
// description: handles making stereograms. holds the depthmap and pattern.
//
// author: sophia daniels
// website: www.catgirlgames.com
// email: sophia@sophiadaniels.com
//
class clsStereogram extends clsImage {
	// private vars
	var $depth;		// holds the depth map
	var $pattern;	// holds the pattern
	var $tile_width = 100;
	var $options;
	// constructor
	function clsStereogram(){
		$this->pattern = new clsPattern();
		$this->depth = new clsDepthMap();
	}
	//
	// make stereograpm
	//
	function makeStereogram(){
		// if the pattern width is smaller than the tile width
		if($this->pattern->loaded){
			if($this->tile_width > $this->pattern->width)
				$this->tile_width = $this->pattern->width;
		}
		// set the width and height
		$this->width = round($this->tile_width + $this->depth->width);
		$this->height = $this->depth->height;
		// create new image
		$this->im = imagecreatetruecolor($this->width,$this->height);
		// first add in the pattern column on the left
		imagecopy($this->im,$this->pattern->firstColumn($this->tile_width,$this->height),0,0,0,0,$this->tile_width,$this->height);
		// now go through pixels of depth map from top to bottom and left to right
		for($x = 0; $x < $this->depth->width; $x++){
			for($y = 0; $y < $this->height; $y++){
				$offset = $this->depth->getDepthAt($x,$y);
				// first copy the source pixel the tile/pattern width and then copy it with the offset
				imagecopy($this->im, $this->im,$x+$this->tile_width,$y,$x,$y,1,1);
				imagecopy($this->im, $this->im,$x+$this->tile_width-$offset,$y,$x,$y,1,1);
			}
		}
		
		$this->loaded = true;
		return true;
	}
}


?>