<?php

require '../vendor/autoload.php';
//require '../src/Fastimage.php';

$uri = 'https://www.northshorelij.com/sites/default/themes/custom/nslij3/img/nslij_logo.png';

echo "\n\n";

$time = microtime(true);
$image = new FastImage($uri);
list($width, $height) = $image->getSize();
echo "FastImage: \n";
echo "Width: ". $width . "px Height: ". $height . "px in " . (microtime(true)-$time) . " seconds \n";

$time = microtime(true);
list($width, $height) = getimagesize($uri);
echo "getimagesize: \n";
echo "Width: ". $width . "px Height: ". $height . "px in " . (microtime(true)-$time) . " seconds \n";
exit;
