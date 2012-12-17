<?php
include_once("clsImage.php");
//
// class: clsDepthMap
// extends: clsImage
// description: handles the depth map image and it's processing.
//
// author: sophia daniels
// website: www.catgirlgames.com
// email: sophia@sophiadaniels.com
//
class clsDepthMap extends clsImage {
	// private vars
	var $max_depth = 20;	// this is the max depth/offset (100% white)
	var $tile_width = 100;	// the width of the pattern so that it can tile over this depth map.
	var $tile_height = 100;	// the height of the pattern so that it can tile over this depth map.
	var $rows = 1;				// the total number of rows (tiles) 				ex:		for($depth->r = 0; $depth->r < $depth->rows; $depth->r++){
	var $cols = 1;				// the total number of columns (tiles)  			ex:		for($depth->c = 0; $depth->c < $depth->col; $depth->c++){
	var $r = 0;					// the current row (tile) 						ex:		for($depth->r = 0; $depth->r < $depth->rows; $depth->r++){
	var $c = 0;					// the current column (tile) 					ex:		for($depth->c = 0; $depth->c < $depth->col; $depth->c++){
	// constructor
	function clsDepthMap($path){
		$this->load($path);
	}
	// returns the 
	function getDepthAt($x,$y){
		$rgb = imagecolorat($this->im, $x+($this->c * $this->tile_width),$y+($this->r * $this->tile_height));
		$colors = imagecolorsforindex($this->im, $rgb);
		return round(($colors['red']+$colors['green']+$colors['blue'])/3/255 * $this->max_depth,0);
	}
	function setPattern($pattern){
		// grab the pattern size...
		$width = $pattern->width;
		$height = $pattern->height;
		// see how the widths compare
		if($this->width > $pattern->width){
			// if depth is wider than pattern see how many rows we can make
			$this->cols = round($this->width / $pattern->width);
		}
		// make sure we have at least 5 passes...
		//if($this->cols < 5)
			//$this->cols = 5;
		// update the width we want...
		$width = floor($this->width/$this->cols);
		// see how the heights compare
		if($this->height > $pattern->height){
			// if depth is wider than pattern see how many rows we can make
			$this->rows = round($this->height / $pattern->height);
		}
		// update the width we want...
		$height = floor($this->height/$this->rows);
		// ok... so the pattern is bigger.... just shrink it down....
		$pattern->resize($width,$height,0,0,$pattern->width,$pattern->height);
		// set tile size stuff to match patter.
		$this->tile_width = $width;
		$this->tile_height = $height;
	}
}
?>