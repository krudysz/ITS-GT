<?php
global $deg;

function get_polar($xrel, $yrel, $ang, $radius) {
    $i = $ang;
    $ang = ($ang * pi())/ 180;
   
    $ix = abs($radius*cos($ang));
    $iy = abs($radius*sin($ang));
   
    if ($i>=0 && $i<=90) {
        $ix = $xrel + $ix;
        $iy = $yrel - $iy;
    }
    if ($i>90 && $i<=180) {
        $ix = $xrel - $ix;
        $iy = $yrel - $iy;
    }
    if ($i>180 && $i<=270) {
        $ix = $xrel - $ix;
        $iy = $yrel + $iy;
    }
    if ($i>270 && $i<=360) {
        $ix = $xrel + $ix;
        $iy = $yrel + $iy;
    }

    $ix = floor($ix);
    $iy = floor($iy);
    //echo ($ix . " $iy<br>");
    $returnvals = array (
                        'x1' => $xrel,
                        'y1' => $yrel,
                        'x2' => $ix,
                        'y2' => $iy
                        );
    return $returnvals;
}

function get_degtotal($degindex)
{
    global $deg;
    if ($degindex == 0 ) {
       return (  $deg[$degindex] );
    }
    else {       
        return ( $deg[$degindex] + get_degtotal($degindex-1) );
    }   
}

$im  = imagecreate (400, 400);
$w   = imagecolorallocate ($im, 255, 255, 255);
$black   = imagecolorallocate ($im, 0, 0, 0);
$red = imagecolorallocate ($im, 255, 0, 0);
$green = imagecolorallocate ($im, 0, 180, 0);

$randcolor[0] = imagecolorallocate($im, 243, 54, 163);
$randcolor[1] = imagecolorallocate($im, 179, 51, 247);
$randcolor[2] = imagecolorallocate($im, 103, 48, 250);
$randcolor[3] = imagecolorallocate($im, 53, 145, 244);
$randcolor[4] = imagecolorallocate($im, 54, 243, 243);
$randcolor[5] = imagecolorallocate($im, 107, 245, 180);
$randcolor[6] = imagecolorallocate($im, 203, 242, 111);
$randcolor[7] = imagecolorallocate($im, 248, 201, 105);

$data[0] = 30;
$data[1] = 20;
$data[2] = 15;
$data[3] = 10;
$data[4] = 8;
$data[5] = 7;
$data[6] = 5;
$data[7] = 5;

$datasum = array_sum($data);

$deg[0] = number_format((30 / $datasum * 360), 2, ".", "");
$deg[1] = number_format((20 / $datasum * 360), 2, ".", "");
$deg[2] = number_format((15 / $datasum * 360), 2, ".", "");
$deg[3] = number_format((10 / $datasum * 360), 2, ".", "");
$deg[4] = number_format((8 / $datasum * 360), 2, ".", "");
$deg[5] = number_format((7 / $datasum * 360), 2, ".", "");
$deg[6] = number_format((5 / $datasum * 360), 2, ".", "");
$deg[7] = number_format((5 / $datasum * 360), 2, ".", "");
//echo ('<pre>');

//print_r($deg);

$datadeg = array();
$datapol = array();
$degbetween = array();
$databetweenpol = array();

for ($i=0; $i < count($deg) ; $i++) {
    $datadeg[$i] = get_degtotal($i);
    $datapol[$i] = get_polar(200, 200, $datadeg[$i], 100);
}

for ($i=0; $i < count($datadeg) ; $i++) {
    /*this is a trick where you take 2deg angle before
    and get the smaller radius so that you can have a pt to
    `imagefill` the chartboundary
    */
    $degbetween[$i] = ($datadeg[$i]-2);
    $databetweenpol[$i] = get_polar(200, 200, $degbetween[$i], 50);
}
/*
print_r($datadeg);
print_r($degbetween);
print_r($databetweenpol);
*/
//exit;

for ($i=0; $i<count($deg); $i++) {
    imageline ($im, 200, 200, $datapol[$i]['x2'], $datapol[$i]['y2'], $black);
}
imagearc($im, 200, 200, 200, 200, 0, 360, $black);

for ($i=0; $i<count($deg); $i++) {
    imagefill ($im, $databetweenpol[$i]['x2'], $databetweenpol[$i]['y2'], $randcolor[$i]);

}

header ("Content-type: image/png");
imagepng($im);
ImageDestroy($im);
?>
