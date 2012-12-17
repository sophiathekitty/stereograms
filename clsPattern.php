<?php
include_once("clsImage.php");
//
// class: clsPattern
// extends: clsImage
// description: handles the pattern image and processing passes using a depth map.
//
// author: sophia daniels
// website: www.catgirlgames.com
// email: sophia@sophiadaniels.com
//
class clsPattern extends clsImage {
	// private vars
	var $pass_cur;		// the current pass that is being processed
	var $pass_prev;		// the previously processed pass.
	var $ready = false;	// have we setup the very first row yet?
	// constructor
	function clsPattern($path){
		$this->load($path);
	}
	//
	// make and return a complete first column of tiled pattern
	//
	function makeTiledColumn($w,$h){
		// create the blank column
		$im = imagecreatetruecolor($w,$h);
		// scale the pattern to fit.
		$this->scale($w,$h);
		// now tile the pattern image ($this->im) onto the new image ($im)
		for($y = 0; $y < $h; $y += $this->height){
			imagecopy($im, $this->im,0,$y,0,0,$this->width,$this->height);
		}
		// return the column.
		return $im;
	}
	//
	// this creates the very first row and gets everything setup. it gets called automatically if it hasn't when you make your first pass.
	//
	function startRow(){
		if($this->loaded){
			// create blank images for current and previous pass
			$this->pass_cur = imagecreatetruecolor($this->width,$this->height);
			$this->pass_prev = imagecreatetruecolor($this->width,$this->height);
			// copy the original pattern image into the new images
			imagecopy($this->pass_cur, $this->im,0,0,0,0,$this->width,$this->height);
			imagecopy($this->pass_prev, $this->im,0,0,0,0,$this->width,$this->height);
			// yay we're ready now
			$this->ready = true;
			return true;
		}
		$this->error = "Pattern Image not loaded.";
		return false;
	}
	//
	// this resets it for a new row. use this one to create new rows.
	//
	function newRow(){
		if($this->loaded){
			if(!$this->ready){
				$this->startRow();
				return true;
			}
			// copy the original over the current and previous pass images.
			imagecopy($this->pass_cur, $this->im,0,0,0,0,$this->width,$this->height);
			imagecopy($this->pass_prev, $this->im,0,0,0,0,$this->width,$this->height);		
			return true;
		}
		$this->error = "Pattern Image not loaded.";
		return false;
	}
	//
	// this runs through the map's depth for the current tile and moves the pixes as needed
	//
	function makePass($map,$im,$xm,$ym){
		if($this->loaded){			// the image needs to be loaded
			if(!$this->ready)		// first row needs to be setup
				$this->startRow();	// just do it for them don't cry about it... "error error first row not created. what? just do it and shut up? ok."
			
			// move the current pass to the previous. (if this is first pass this is redundent but eh. fix later?)
			imagecopy($this->pass_prev, $this->pass_cur,0,0,0,0,$this->width,$this->height);
			
			// now run through all the pixels!! and uhm shift them in the cur
			for($x = 0; $x < $this->width; $x++){
				for($y = 0; $y < $this->height; $y++){
					// get offset
					$offset = $map->getDepthAt($x,$y);
					if($x - $offset >= 0){
						// ok we can actually copy it onto a a real pixel and not uhm... off to the left of the image.... idk?
						imagecopy($this->pass_cur, $this->pass_prev,$x-$offset,$y,$x,$y,1,1);
					} else {
						imagecopy($im, $this->pass_prev,$x-$offset+$this->width,$y,$x,$y,1,1);
					}
					imagecopy($im, $this->pass_prev,$x-$offset+$mx,$y+$my,$x,$y,1,1);
				}
			}
			// and noowww we gotta return the current pass to be added to the full image. it all might makes sense?
			return $this->pass_cur;
		}
		$this->error = "Pattern Image not loaded.";
		return false;
	}	
}
?>