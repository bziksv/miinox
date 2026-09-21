<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;

if (!Loader::includeModule('sale')) {
	return;
}

$count = 0;
$basket = Basket::loadItemsForFUser(Fuser::getId(true), SITE_ID);
foreach ($basket as $item) {
	if ($item->isDelay() || $item->getField('ORDER_ID')) {
		continue;
	}
	$count++;
}

$arResult['NUM_PRODUCTS'] = $count;
