<?php
ob_start();
session_start();

  
header("Content-type: image/jpeg");

$captchaStr = generateRandomString();
$_SESSION["captcha"] = $captchaStr;
$im = imagecreatetruecolor(150,40);
 
$white = imagecolorallocate($im,255,255,255);
$black = imagecolorallocate($im,0,0,0);
$grey = imagecolorallocate($im,125,125,125);

imagefill($im,0,0,$white);
imagettftext($im,20,0,12,32,$grey,"font.ttf",$captchaStr);
imagettftext($im,20,0,10,30,$black,"font.ttf",$captchaStr);

imagejpeg($im);
imagedestroy($im);
    
//Functions ********************************************
function generateRandomString($length = 5) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

?>