<?php

header('Content-Type: application/json');

require './vendor/autoload.php';

$uri = $_REQUEST['image'] ? : false;

$image_details = new stdClass();

if ($uri)
{
	$image = new FastImage($uri);
	list($image_details->width, $image_details->height) = $image->getSize();
	$image_details->type = $image->getType();
}
else
{
	$image_details->width  = 0;
	$image_details->height = 0;
	$image_details->type   = false;
}

echo json_encode($image_details);

exit;
