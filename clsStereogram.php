<?php
include_once("clsImage.php");
include_once("clsPattern.php");
include_once("clsDepthMap.php");
//
// class: clsDepthMap
// extends: clsImage
// description: handles the depth map image and it's processing.
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
	// constructor
	function clsStereogram($pattern_path,$depth_path){
		//die($pattern_path."<br>".$depth_path);
		// load the pattern and depth map
		$this->pattern = new clsPattern($pattern_path);
		// if the pattern width is smaller than the tile width
		if($this->tile_width > $this->pattern->width)
			$this->tile_width = $this->pattern->width;
		$this->depth = new clsDepthMap($depth_path);
		// now do some math to figure out if we need to resize the images at all.
		//$this->depth->setPattern($this->pattern);
		// create new image
		$this->width = round($this->tile_width + $this->depth->width);
		$this->height = $this->depth->height;
		$this->im = imagecreatetruecolor($this->width,$this->height);
	}
	//
	// make stereograpm
	//
	function makeStereogram(){
		// new method.... (planning?)
		
		// first add in the pattern column on the left
		imagecopy($this->im,$this->pattern->makeTiledColumn($this->tile_width,$this->height),0,0,0,0,$this->tile_width,$this->height);
		// now go through pixels of depth map from top to bottom and left to right
		for($x = 0; $x < $this->depth->width; $x++){
			for($y = 0; $y < $this->height; $y++){
				$offset = $this->depth->getDepthAt($x,$y);
				// first copy the source pixel the tile/pattern width and then copy it with the offset
				imagecopy($this->im, $this->im,$x+$this->tile_width,$y,$x,$y,1,1);
				imagecopy($this->im, $this->im,$x+$this->tile_width-$offset,$y,$x,$y,1,1);
			}
		}
		
		/*
		// old method.... 
		for($this->depth->r = 0; $this->depth->r < $this->depth->rows; $this->depth->r++){
			$this->pattern->newRow();
			imagecopy($this->im, $this->pattern->im,0,$this->depth->r*$this->depth->tile_height,0,0,$this->depth->tile_width,$this->depth->tile_height);
			$x = $this->depth->tile_width;
			for($this->depth->c = 0; $this->depth->c < $this->depth->cols; $this->depth->c++){
				imagecopy($this->im, $this->pattern->makePass($this->depth,$this->im,$x,$this->depth->r*$this->depth->tile_height),$x,$this->depth->r*$this->depth->tile_height,0,0,$this->depth->tile_width,$this->depth->tile_height);
				$x += $this->depth->tile_width;
			}
		}
		*/
		$this->loaded = true;
		return true;
	}
}
?>