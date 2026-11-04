<?php
define('NEED_AUTH', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

global $USER;

$userName = '';
if (is_object($USER)) {
    $userName = trim((string)$USER->GetFullName());
    if ($userName === '') {
        $userName = (string)$USER->GetLogin();
    }
}

$backurl = (isset($_REQUEST['backurl']) && is_string($_REQUEST['backurl'])) ? $_REQUEST['backurl'] : '';
$backurlSafe = $backurl !== ''
    && strpos($backurl, '/') === 0
    && strpos($backurl, '//') !== 0
    && stripos($backurl, '/login') !== 0
    && !preg_match('/[\r\n]/', $backurl);
$target = $backurlSafe ? $backurl : '/';
?>
<script>
	BX.localStorage.set("eshop_user_name", "<?=CUtil::JSEscape($userName)?>", 604800);
	document.location.replace("<?=CUtil::JSEscape($target)?>");
</script>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
