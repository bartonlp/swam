<?php
  // Button
  // Arguments:
  // text : string
  // s : integer, the font size
  // font: string, the name of the font
  // xpad, ypad: integer, x and y pad in pixles
  // bgcolor: string, background color in HTML hex less the #
  // txtcolor: string, text color
  // shadow: string, shadow color
  // width: integer, width in pixels of the box
  // All args except text have defaults -- if text not there then just a box

  Header("Content-type: image/png");

//  $fontfile = "/httpd/html/fonts/";
  $fontfile = "/zupons/fonts/";
  if(!isset($s)) $s=11; // If no size then use 11 point

  if(!isset($font)) $fontfile .= "TIMES.TTF"; // If no font supplied use times
  else {
    // If a font was supplied then make it into a full file name by adding the .ttf if
    // needed. Make it uppercase because that's how the files are on this server!

    $font = strtoupper($font);

    if(!strstr($font, ".TTF"))
      $font .= ".TTF";

    $fontfile .= $font;
  }

  if(!isset($bgcolor)) $bgcolor = "2c6daf"; // Default background is a light blue
  if(!isset($txtcolor)) $txtcolor = "ffffff"; // default text color is white
  if(!isset($shadow)) $shadow = "000000"; // default drop shadow is black

  // Get the size of the box the TrueType font we specified will fit into.
  // NOTE: These numbers are from the baseline, that is for a letter like "A"
  // the baseline is at the bottom of the letter, while for a letter like "g"_
  // the base line is where the underline is. The part above the baseline is a 
  // negetive number while the part the extends below the baseline is positive!

  $size = imagettfbbox($s,0, $fontfile, $text);
  $dx = abs($size[2]-$size[0]);

  if(!isset($width)) {  // If no width specified use dx
    $width = $dx;
  } 

  $tdy = abs($size[5]-$size[3]);  // The vertical size of the font

  if(!isset($xpad)) $xpad = 12; // default for X pad
  if(!isset($ypad)) $ypad = 12; // and Y pad. These are the amount before and after the text.

  // If the text has leters like "jygp" etc. that extend below the baseline
  // we need to compute a DROP. These values came from expermentation and I
  // don't know why I have to add 2.
  // $size[1] is the lower left side.

  $drop = 0;

  if($size[1] >= 0) 
    $drop = $size[1] + 2;

  // To make the buttons for a given font size the same height use the tallest and 
  // lowest letters to compute the full font height

  $size = imagettfbbox($s,0, $fontfile, "Cg");
  $dy = abs($size[5]-$size[3]);

  $im = imagecreate($width+($xpad*2), $dy+($ypad*2));

  // Create the three colors from what may have been shipped in

  $bgcolor = ImageColorAllocate($im, hexdec(substr($bgcolor, 0, 2)), hexdec(substr($bgcolor, 2, 2)), hexdec(substr($bgcolor, 4, 2)));
  $shadow = ImageColorAllocate($im, hexdec(substr($shadow, 0, 2)), hexdec(substr($shadow, 2, 2)), hexdec(substr($shadow, 4, 2)));
  $txtcolor = ImageColorAllocate($im, hexdec(substr($txtcolor, 0, 2)), hexdec(substr($txtcolor, 2, 2)), hexdec(substr($txtcolor, 4, 2)));

  // Calculate the width and heights

  $w = $width+(2*$xpad);
  $ht = $tdy+($ypad)-$drop; // subtract the drop!
  $h =  $dy+(2*$ypad);

  // Give the button a 3D effect by adding a shadow

  ImageRectangle($im,0,0,$w-1,$h-1,$shadow);
  ImageRectangle($im,0,0,$w-2,$h-2,$shadow);
  ImageRectangle($im,0,0,$w-3,$h-3,$shadow);
  ImageRectangle($im,0,0,$w,$h,$txtcolor);

  // Position the text in the middle

  $ws = (int)($w/2) - (int)($dx/2);

  // Add a shadow to give 3D effect

  ImageTTFText($im, $s, 0, $ws, $ht, $shadow, $fontfile, $text);
  ImageTTFText($im, $s, 0, $ws, $ht-1, $txtcolor, $fontfile, $text);

  ImagePng($im);
  ImageDestroy($im);
?>
