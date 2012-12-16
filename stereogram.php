<?php
include("clsStereogram.php");
$sg = new clsStereogram("patterns/noise".rand(1,5).".jpg","depthmaps/oblivion".rand(1,9).".jpg");
$sg->makeStereogram();
$sg->display();
?>