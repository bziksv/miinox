<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

$id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$quantity = !empty($_REQUEST['quantity']) ? (float)str_replace(',', '.', (string)$_REQUEST['quantity']) : 1.0;
if ($quantity <= 0) {
	$quantity = 1.0;
}

if ($id <= 0) {
	return;
}

if (!CModule::IncludeModule('catalog') || !CModule::IncludeModule('sale')) {
	return;
}

// 1С часто отдаёт QUANTITY=0 + запрет покупки при нуле → Add2Basket падает с «Товар отсутствует»
if (function_exists('ensureCatalogProductOrderable')) {
	ensureCatalogProductOrderable($id, 39);
}

Add2BasketByProductID($id, $quantity);
