<?php
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('NOT_CHECK_PERMISSIONS', true);
define('BX_PUBLIC_TOOLS', true);

$_SERVER['HTTP_ACCEPT_ENCODING'] = '';

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$cpt = new CCaptcha();
$cpt->SetImageSize(240, 72);
$cpt->SetTextWriting(0, 0, 18, 40, 44, 34);
$cpt->SetEllipsesNumber(0);
$cpt->SetLinesNumber(0);
$cpt->SetTextTransparent(false, 0);
$cpt->SetBGColor([255, 255, 255]);
$cpt->SetTextColor([20, 34, 45]);
$cpt->SetBorderColor([213, 224, 230]);

$sid = (isset($_GET['captcha_sid']) && is_string($_GET['captcha_sid'])) ? $_GET['captcha_sid'] : '';
if ($sid !== '' && $cpt->InitCode($sid)) {
    $cpt->Output();
} else {
    $cpt->OutputError();
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
