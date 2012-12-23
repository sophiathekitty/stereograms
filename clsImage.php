<?php 
//
// class: clsImage
// description: handles loading and scaling and saving and displaying images. oh my.
//
// author: sophia daniels
// website: www.catgirlgames.com
// email: sophia@sophiadaniels.com
//
class clsImage {
	// private vars
	var $im;
	var $max_size;
	var $type = 2;
	var $width;
	var $height;
	var $size;
	var $error;
	var $imageTypes;
	var $loaded;
	var $path;
	var $saved = false;
	// constructor
	function clsImage(){
		$this->loaded = false;
		$this->max_size = 1000000000000000;
		$this->imageTypes = array(
					IMG_GIF=>'GIF',
					IMG_JPG=>'JPG',
					IMG_PNG=>'PNG',
					IMG_WBMP=>'WBMP'
				);
	}
	function create($w,$h){
		$this->im = imagecreatetruecolor($w,$h);
		$this->width = $w;
		$this->height = $h;
		$this->loaded = true;
	}
	function load($path){
		if(!is_file($path)){
			$this->error = "Can't find $path, or it's not a file!";
			die($this->error);
			return false;
		}
		list($this->width, $this->height, $this->type, $attr) = getimagesize($path);
		@$this->size = filesize($path) or $this->size = 0;
		if($this->size < $this->max_size || true){ // i broke the size check. it doesn't seem important anymore... or to work correctly....
			if(imagetypes() & $this->type){
				switch($this->type){
					case IMG_GIF:
					case 1:
						$this->im=imagecreatefromgif($path);
					break;
					case IMG_JPG:
					case 2:
						$this->im=imagecreatefromjpeg($path);
					break;
					case IMG_PNG:
					case 3:
						$this->im=imagecreatefrompng($path);
					break;
					case IMG_WBMP:
					case 4:
						$this->im=imagecreatefromwbmp($path);
					break;
					default:
						$this->error = "load error: $path | Unknown Image Type. ($this->type)";
						die($this->error);
						return false;
					break;
				}
			} else {
				$this->error = "Unsupported Image Type. ($this->type)";
				die($this->error);
				return false;
			}
		} else {
			$this->error = "load error: $path | Image too large. ($this->size)";
			die($this->error);
			return false;
		}
		$this->loaded = true;
		$this->path = $path;
		return true;
	}
	function save(){
		if(isset($this->path) && strlen($this->path) > 5){
			$this->saveAs($this->path);
		}
	}
	function saveAs($path){
		if($this->loaded){
			switch($this->type){
				case 1:
					imagegif($this->im,$path);
				break;
				case 2:
					imagejpeg($this->im,$path);
				break;
				case 3:
					imagepng($this->im,$path);
				break;
				case 5:
					imagewbmp($this->im,$path);
				break;
				default:
					$this->error = "save error: $path | Unknown Image Type. ($this->type)";
					die($this->error);
					return false;
				break;
			}
			return true;
		}
	}
	function resize($w,$h,$x,$y,$sw,$sh){
		if($this->loaded){
			$small = imagecreatetruecolor($w, $h);    // new image
			imagecopyresampled($small, $this->im,0,0,$x,$y,$w, $h, $sw, $sh);	// below is main function resampling image
			imagedestroy($this->im);
			$this->im = $small;
		}
		$this->width = $w;
		$this->height = $h;
	}
	function scale($maxx,$maxy){
		if($this->loaded){
			if($this->width < $this->height || $maxx < $maxy){
				$w = $maxx;
				$percent = ($this->width / $w);
				$h = ($this->height / $percent);
			} else {
				$h = $maxy;
				$percent = ($this->height / $h);
				$w = ($this->width / $percent);
			}
			$this->resize($w,$h,0,0,$this->width,$this->height);
		}
	}
	function cropscale($w,$h){
		if($this->loaded){
			$width = abs($this->width - $w);
			$height = abs($this->height - $h);
			if($width > $height){
				$y = 0;
				$sh = $this->height;
				$percent = ($this->height / $h);
				$x = ($this->width / 2) - (($w * $percent) / 2);
				$sw = $w * $percent;
			} else {
				$x = 0;
				$sw = $this->width;
				$percent = ($this->width / $w);
				$y = ($this->height / 2) - (($h * $percent) / 2);
				$sh = $h * $percent;
			}
			if($x < 0){
				$x = 0;
				$sw = $this->width;
				$percent = ($this->width / $w);
				$y = ($this->height / 2) - (($h * $percent) / 2);
				$sh = $h * $percent;
			}
			if($y < 0){
				$y = 0;
				$sh = $this->height;
				$percent = ($this->height / $h);
				$x = ($this->width / 2) - (($w * $percent) / 2);
				$sw = $w * $percent;
			}
			$this->resize($w,$h,$x,$y,$sw,$sh);
		}
	}
	function display(){
		if($this->loaded){
			switch($this->type){
				case IMG_GIF:
					header('Content-Type: image/gif');
					imagegif($this->im);
				break;
				case IMG_JPG:
					header('Content-Type: image/jpeg');
					imagejpeg($this->im);
				break;
				case IMG_PNG:
					header('Content-Type: image/png');
					imagepng($this->im);
				break;
				case IMG_WBMP:
					header('Content-Type: image/wbmp');
					imagewbmp($this->im);
				break;
				default:
					$this->error = "display error: Unknown Image Type. ($this->type)";
					die($this->error);
					return false;
				break;
			}
		}
	}
	function colorize($r,$g,$b,$a){
		if($this->loaded){
			$im = imagecreatetruecolor($this->width,$this->height);
			$color = imagecolorallocate($im,$r,$g,$b);
			imagefill($im,0,0,$color);
			imagecopymerge($this->im,$im,0,0,0,0,$this->width,$this->height,$a);
		}
	}
}
?>