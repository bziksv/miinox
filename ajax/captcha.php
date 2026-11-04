<?php
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('NOT_CHECK_PERMISSIONS', true);
define('BX_PUBLIC_TOOLS', true);

$_SERVER['HTTP_ACCEPT_ENCODING'] = '';

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$sid = (isset($_GET['captcha_sid']) && is_string($_GET['captcha_sid'])) ? $_GET['captcha_sid'] : '';
$cpt = new CCaptcha();
if ($sid === '' || !$cpt->InitCode($sid)) {
    $cpt->OutputError();
    return;
}

$code = preg_replace('/\D+/', '', (string)$cpt->code);
$chars = preg_split('//u', $code, -1, PREG_SPLIT_NO_EMPTY);
if (!$chars) {
    $cpt->OutputError();
    return;
}

$font = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/fonts/font.ttf';
$fontSize = 36;
$padX = 16;
$gap = 14;
$widths = [];
$total = 0;
foreach ($chars as $char) {
    $bounds = imagettfbbox($fontSize, 0, $font, $char);
    $width = abs($bounds[2] - $bounds[0]) + 4;
    $widths[] = $width;
    $total += $width;
}

$imageWidth = $padX * 2 + $total + $gap * (count($chars) - 1);
$imageHeight = 84;
$image = imagecreatetruecolor($imageWidth, $imageHeight);
$background = imagecolorallocate($image, 255, 255, 255);
$ink = imagecolorallocate($image, 20, 34, 45);
imagefilledrectangle($image, 0, 0, $imageWidth, $imageHeight, $background);

$x = $padX;
foreach ($chars as $index => $char) {
    imagettftext($image, $fontSize, 0, $x, 58, $ink, $font, $char);
    $x += $widths[$index] + $gap;
}

header('Expires: Sun, 1 Jan 2000 12:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: image/jpeg');
imagejpeg($image, null, 95);
imagedestroy($image);
