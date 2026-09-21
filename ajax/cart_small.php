<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;

$count = 0;
if (Loader::includeModule('sale')) {
	$fuserId = Fuser::getId(true);
	$basket = Basket::loadItemsForFUser($fuserId, SITE_ID);
	foreach ($basket as $item) {
		if ($item->isDelay() || $item->getField('ORDER_ID')) {
			continue;
		}
		$count++;
	}
	\Bitrix\Sale\BasketComponentHelper::updateFUserBasket($fuserId, SITE_ID);
}
?>
<a href="/cart/">
	<span class="glipf-cart"></span>
	<span class="head-cart_number"><?=(int)$count?></span>
</a>
