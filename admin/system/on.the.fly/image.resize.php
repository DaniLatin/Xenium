<?php

session_cache_limiter('');
header('Content-type:image/' . strtolower(substr(strrchr($_GET['filename'],"."),1)));
require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');

$longExpiryOffset = 315360000;
header ( "Cache-Control: public, max-age=" . $longExpiryOffset );
header ( "Expires: " . gmdate ( "D, d M Y H:i:s", time () + $longExpiryOffset ) . " GMT" );

$filename = $_GET['filename'];
$file_year = $_GET['file_year'];
$file_month = $_GET['file_month'];
$resize_x = $_GET['resize_x'];
$resize_y = $_GET['resize_y'];
//if (isset($_GET['resize_mode'])){ $resize_mode = $_GET['resize_mode']; } else { $resize_mode = 'ZEBRA_IMAGE_CROP_CENTER'; };


if (!isset($_GET['resize_mode']))
{
    $resize_mode = 6;
    $resize_mode_name = 'ZEBRA_IMAGE_CROP_CENTER';
}
else
{
    $resize_mode = $_GET['resize_mode'];
    if ($resize_mode == 0)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_BOXED';
    }
    elseif ($resize_mode == 1)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_NOT_BOXED';
    }
    elseif ($resize_mode == 2)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_CROP_TOPLEFT';
    }
    elseif ($resize_mode == 3)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_CROP_TOPCENTER';
    }
    elseif ($resize_mode == 4)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_CROP_TOPRIGHT';
    }
    elseif ($resize_mode == 5)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_CROP_MIDDLELEFT';
    }
    elseif ($resize_mode == 6)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_CROP_CENTER';
    }
    elseif ($resize_mode == 7)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_CROP_MIDDLERIGHT';
    }
    elseif ($resize_mode == 8)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_CROP_BOTTOMLEFT';
    }
    elseif ($resize_mode == 9)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_CROP_BOTTOMCENTER';
    }
    elseif ($resize_mode == 10)
    {
        $resize_mode_name = 'ZEBRA_IMAGE_CROP_BOTTOMRIGHT';
    }
}


$resized_file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/image/' . $file_year . '/' . $file_month . '/' . $resize_x . 'x' . $resize_y . '_' . $resize_mode_name . '/' . $filename;
$redirect = 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/image/' . $file_year . '/' . $file_month . '/' . $resize_x . 'x' . $resize_y . '_' . $resize_mode_name . '/' . $filename;

$file_extension = strtolower(substr(strrchr($filename,"."),1));

//$headers = apache_request_headers(); 

if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
  $if_modified_since = preg_replace('/;.*$/', '',   $_SERVER['HTTP_IF_MODIFIED_SINCE']);
} else {
  $if_modified_since = '';
}

if (file_exists($resized_file))
{
    // Checking if the client is validating his cache and if it is current.
    //if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($resized_file))) {
    //header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($resized_file)).' GMT', true, 304);
    /*if (isset($if_modified_since) && (strtotime($if_modified_since) == filemtime($resized_file))) {
        // Client's cache IS current, so we just respond '304 Not Modified'.
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($resized_file)).' GMT', true, 304);
    } else {
        // Image not cached or cache outdated, we respond '200 OK' and output the image.
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($resized_file)).' GMT', true, 200);
        header('Content-Length: '.filesize($resized_file));
        //header('Content-Type: image/png');
        print file_get_contents($resized_file);
    }*/
    //header( 'Location: ' . $redirect ) ;
    //readfile($resized_file);
    header( 'Location: ' . $redirect ) ;
}
else
{
    $image = new Zebra_Image();
    $image->source_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/image/' . $file_year . '/' . $file_month . '/' . $filename;
    File::create_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads/image/' . $file_year . '/' . $file_month . '/' . $resize_x . 'x' . $resize_y . '_' . $resize_mode_name . '/');
    $image->target_path = $resized_file;
    if (!$image->resize($resize_x, $resize_y, $resize_mode)) show_error($image->error, $image->source_path, $image->target_path);
    //readfile($resized_file);
    print file_get_contents($resized_file);
}

?>