<?php
include("clsStereogram.php");
//
// set up defaults
//
$width = 800;
$height = 600;
$colors = rand(4,8);
$spreckles = rand(4,8);
$random = 128;
$random_speckle = 100;
$random_alpha = 50;
$speckle_alpha = 60;
$speckle_count = 600;
$max = 10;
//
// get properties
//
if(isset($_GET['w']))
	$width = $_GET['w'];
if(isset($_GET['h']))
	$height = $_GET['h'];
if(isset($_GET['c']))
	$colors = $_GET['c'];
if(isset($_GET['s']))
	$spreckles = $_GET['s'];
if(isset($_GET['r']))
	$random = $_GET['r'];
if(isset($_GET['rs']))
	$random_spreckle = $_GET['rs'];
if(isset($_GET['ra']))
	$random_alpha = $_GET['ra'];
if(isset($_GET['a']))
	$speckle_alpha = $_GET['a'];
if(isset($_GET['color']))
	$color = hexrgb($_GET['color']);
if(isset($_GET['speckle']))
	$speckle = hexrgb($_GET['speckle']);
if(isset($_GET['sc']))
	$speckle_count = $_GET['sc'];
if(isset($_GET['dm']))
	$max = $_GET['dm'];


function hexrgb($hexstr) {
    $int = hexdec($hexstr);
    return array("red" => 0xFF & ($int >> 0x10), "green" => 0xFF & ($int >> 0x8), "blue" => 0xFF & $int);
}

//make the image and stuff
$sg = new clsStereogram();
$sg->depth->create($width,$height);
$sg->depth->addSet(1,10,4);
$sg->pattern->speckle_count = $speckle_count;
$sg->pattern->speckle_max = $max;
$sg->pattern->colors->addColors($colors,$color['red'],$color['green'],$color['blue'],0,$random);
$sg->pattern->speckle_colors->addColors($spreckles,$speckle['red'],$speckle['green'],$speckle['blue'],$speckle_alpha,$random_speckle,$random_alpha);
$sg->makeStereogram();
$sg->display();
?>