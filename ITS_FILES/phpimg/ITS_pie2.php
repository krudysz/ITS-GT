<?php
header ("Content-type: image/png");
$diameter = 100;
$radius = $diameter / 2;
$centerX = $radius;
$centerY = $radius;

$im = @ImageCreate ($diameter, $diameter)
    or die ("Cannot Initialize new GD image stream");

$background = ImageColorAllocate ($im, 0, 0, 0);
$red = ImageColorAllocate ($im, 176, 0, 0);
$blue = ImageColorAllocate ($im, 0, 176, 0);

function fill_arc($start, $end, $color) {
    global $diameter, $centerX, $centerY, $im, $radius;
    imagearc($im, $centerX, $centerY, $diameter, $diameter, $start, $end, $color);
    imageline($im, $centerX, $centerY, $centerX + cos(deg2rad($start)) * $radius, $centerY + sin(deg2rad($start)) * $radius, $color);
    imageline($im, $centerX, $centerY, $centerX + cos(deg2rad($end)) * $radius, $centerY + sin(deg2rad($end)) * $radius, $color);
    imagefill ($im,$centerX + $radius * 0.5 *cos(deg2rad($start+($end-$start)/2)), $centerY + $radius * 0.5 * sin(deg2rad($start+($end-$start)/2)), $color);
    }


fill_arc(0,90,$red);
fill_arc(90,360,$blue);
// Will make a red filled arc, starting at 0 degrees, ending at 30 degrees

ImagePng ($im);
?>
