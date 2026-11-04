<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$miinoxShareNotice = '';
$miinoxShareIds = '';
if (isset($_REQUEST['share']) && is_string($_REQUEST['share']) && $_REQUEST['share'] !== '') {
	$miinoxShareNotice = function_exists('miinoxApplySharedCart')
		? miinoxApplySharedCart($_REQUEST['share'])
		: 'Не удалось открыть корзину по ссылке.';
	if (function_exists('miinoxTagSharedBasketLines')) {
		$miinoxShareIds = implode(',', miinoxTagSharedBasketLines($_REQUEST['share']));
	}
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';
$APPLICATION->SetTitle('Корзина');
?>
<main class="main-content" id="miinox-cart-page" data-share-notice="<?=htmlspecialcharsbx($miinoxShareNotice)?>" data-share-ids="<?=htmlspecialcharsbx($miinoxShareIds)?>"></main>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
