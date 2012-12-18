<?php
include("clsStereogram.php");
$sg = new clsStereogram();
$sg->makeStereogram("patterns/noise".rand(1,5).".jpg","depthmaps/oblivion".rand(1,9).".jpg");
$sg->display();
?>