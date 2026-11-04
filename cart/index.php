<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$miinoxShareNotice = '';
if (isset($_REQUEST['share']) && is_string($_REQUEST['share']) && $_REQUEST['share'] !== '') {
	$miinoxShareNotice = function_exists('miinoxApplySharedCart')
		? miinoxApplySharedCart($_REQUEST['share'])
		: 'Не удалось открыть корзину по ссылке.';
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle('Корзина');
?>
<main class="main-content" id="miinox-cart-page" data-share-notice="<?=htmlspecialcharsbx($miinoxShareNotice)?>"></main>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
