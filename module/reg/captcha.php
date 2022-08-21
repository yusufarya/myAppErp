<?php
/*
 * @link http://phpform.net/math_captcha.php
 */
session_start();
// value 1
$numOne = rand(1, 9);
// value2
$numTwo = rand(1, 9);
// total
$numero = $numOne + $numTwo;
// math string
$display = $numOne . '+' . $numTwo . '=' . ' '. '?';

// set session variable to total
$_SESSION['check'] = $numero;
$_SESSION['checkReg'] = $numero;
/*
 * @link http://www.digimantra.com/technology/create-image-in-php-using-gd-with-different-font-size/
 */
// set image size (pixels)
// imagecreate( [width], [height] )
$img = imagecreate( 80, 38 );
// choose a bg color, you can play with the rgb values
$color = imagecolorallocatealpha( $img, 0, 0, 0, 127 );	// Add
imagefill( $img, 0, 0, $color );
imagesavealpha( $img, true );
// imagecolorallocate( [image], [red], [green], [blue] )
$background = imagecolorallocate( $img, 255, 255, 255 );
//chooses the text color
// imagecolorallocate( [image], [red], [green], [blue] )
$text_colour = imagecolorallocate( $img, 33, 37, 41 );
//pulls the value passed in the URL
$text = $display;
// place the font file in the same dir level as the php file
$font = 'ethopool.ttf';
//this function sets the font size, places to the co-ords
// imagettftext( [image], [size], [angle], [x], [y], [color], [fontfile], [text] )
imagettftext($img, 16, 0, 0, 26, $text_colour, $font, $text);
//alerts the browser abt the type of content i.e. png image
header( 'Content-type: image/png' );
//now creates the image
imagepng( $img );
//destroys used resources
imagecolordeallocate( $text_color );
imagecolordeallocate( $background );
imagedestroy( $img );
