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
	function clsDepthMap(){
		//$this->load($path);
	}
	// returns the 
	function getDepthAt($x,$y){
		$rgb = imagecolorat($this->im, $x+($this->c * $this->tile_width),$y+($this->r * $this->tile_height));
		$colors = imagecolorsforindex($this->im, $rgb);
		return round(($colors['red']+$colors['green']+$colors['blue'])/3/256 * $this->max_depth,0);
	}
	//
	// addObject(depth
	// depth: the depth of the object
	// path: image path 
	// 
	function addObject($depth,$path){
		$obj = new clsDepthObject($path);
		$obj->setDepth($depth);
		imagecopy($this->im,$obj->im,rand(0,$this->width-$obj->width),rand(0,$this->height-$obj->height),0,0,$obj->width,$obj->height);
	}
	// 
	// add a set
	// 
	function addSet($min,$max,$total){
		$l = $total;
		for($i = 0; $i < $l; $i++){
			$d = ($i)/$l * 250 + 5;
			$this->addObject($d,"depthmaps/shapes/shape".rand($min,$max).".png");
		}
	}
}
//
// class: DepthObjectSet
// extends: ArrayObject
// description: an array of depth objects.
//
// author: sophia daniels
// website: www.catgirlgames.com
// email: sophia@sophiadaniels.com
//
class DepthObjectSet extends ArrayObject {
	// add folder
	
	// add image
	
	// this should allow us to use this class like an array. whenever needed.... hopefully....
	public function offsetGet($name) { 
		return call_user_func_array(array(parent, __FUNCTION__), func_get_args()); 
	} 
	public function offsetSet($name, $value) { 
		return call_user_func_array(array(parent, __FUNCTION__), func_get_args()); 
	} 
	public function offsetExists($name) { 
		return call_user_func_array(array(parent, __FUNCTION__), func_get_args()); 
	} 
	public function offsetUnset($name) { 
		return call_user_func_array(array(parent, __FUNCTION__), func_get_args()); 
	} 	
}
//
// class: clsDepthObject 
// extends: clsImage
// description: handles the depth map object images for building dynamic depth maps
//
// author: sophia daniels
// website: www.catgirlgames.com
// email: sophia@sophiadaniels.com
//
class clsDepthObject extends clsImage {
	// so we can remember the depth
	var $depth;
	// because these are always images that need to be loaded
	function clsDepthObject($path){
		$this->load($path);
		// should go through and figure out the color situation....
	}
	function setDepth($depth){
		// remember depth;
		$this->depth = $depth;
		// find the white color
		imagecolorset($this->im,0,$depth,$depth,$depth);
		$r = rand(-90,90);
		$this->im = imagerotate($this->im,$r,1);
		$this->width = imagesx($this->im);
		$this->height = imagesy($this->im);
	}
	
}
?>