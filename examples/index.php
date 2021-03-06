<?php 

require_once '../src/PatternCreator.php';

/**
 ===========================
 	Example 1 : 
 ===========================
 */

$pattern = new TheSecondDre\PatternCreator([
		'width' 	=>  1000,
		'height' 	=>  1000,
		'color' 	=>  '#7700ff',
		'pattern' 	=>  'square3',
	]);

$img = $pattern->createPattern('browser');
die();

/**
 ===========================
 	Example 2 : 
 ===========================
 */

$img = $pattern->createPattern('save', '/Users/andre/www/PatternCreator/src/img_patterns/zefij.jpg');

/**
 ===========================
 	Example 3 : 
 ===========================
 */

$img = $pattern->createPattern('phpgd');
header('Content-Type: image/jpeg');
imagejpeg($img);
die();


?>