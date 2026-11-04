<?php

use Bitrix\Main\Application;

function miinoxSharedCartEnsureTable(): bool
{
	try {
		$connection = Application::getConnection();
		$connection->queryExecute(
			'CREATE TABLE IF NOT EXISTS miinox_shared_cart (
				ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
				CODE VARCHAR(16) NOT NULL,
				FUSER_ID INT UNSIGNED NOT NULL DEFAULT 0,
				ITEMS MEDIUMTEXT NOT NULL,
				CREATED DATETIME NOT NULL,
				PRIMARY KEY (ID),
				UNIQUE KEY UX_MIINOX_SHARED_CART_CODE (CODE),
				KEY IX_MIINOX_SHARED_CART_FUSER (FUSER_ID, CREATED)
			)'
		);
		return true;
	} catch (\Throwable $e) {
		return false;
	}
}

function miinoxSharedCartCode(): string
{
	$alphabet = 'abcdefghjkmnpqrstuvwxyz23456789';
	$length = strlen($alphabet);
	$bytes = random_bytes(10);
	$code = '';
	for ($i = 0; $i < 10; $i++) {
		$code .= $alphabet[ord($bytes[$i]) % $length];
	}
	return $code;
}

function miinoxSharedCartUrl(string $code): string
{
	$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		|| (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
	$host = (string)($_SERVER['HTTP_HOST'] ?? '');
	return ($https ? 'https' : 'http') . '://' . $host . '/cart/s/' . $code . '/';
}

function miinoxSharedCartProps($item): array
{
	$props = [];
	$collection = $item->getPropertyCollection();
	if (!$collection) {
		return $props;
	}
	foreach ($collection as $prop) {
		$code = (string)$prop->getField('CODE');
		if ($code === 'CATALOG.XML_ID' || $code === 'PRODUCT.XML_ID') {
			continue;
		}
		$name = trim((string)$prop->getField('NAME'));
		$value = trim((string)$prop->getField('VALUE'));
		if ($name === '' || $value === '') {
			continue;
		}
		$props[] = [
			'NAME' => mb_substr($name, 0, 100),
			'CODE' => mb_substr($code, 0, 50),
			'VALUE' => mb_substr($value, 0, 255),
			'SORT' => (int)$prop->getField('SORT'),
		];
	}
	return $props;
}

function miinoxSharedCartSnapshot(): array
{
	$items = [];
	if (!\Bitrix\Main\Loader::includeModule('sale')) {
		return $items;
	}
	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(true), SITE_ID);
	foreach ($basket as $item) {
		if ($item->isDelay() || $item->getField('ORDER_ID') || $item->getField('MODULE') !== 'catalog') {
			continue;
		}
		$productId = (int)$item->getProductId();
		$quantity = (float)$item->getQuantity();
		if ($productId <= 0 || $quantity <= 0) {
			continue;
		}
		if ($quantity > 100000) {
			$quantity = 100000;
		}
		$items[] = [
			'PRODUCT_ID' => $productId,
			'QUANTITY' => $quantity,
			'PROPS' => miinoxSharedCartProps($item),
		];
	}
	return $items;
}

function miinoxCreateSharedCart(): array
{
	if (!\Bitrix\Main\Loader::includeModule('sale') || !miinoxSharedCartEnsureTable()) {
		return ['error' => 'Не удалось сохранить ссылку.'];
	}

	$items = miinoxSharedCartSnapshot();
	if (!$items) {
		return ['error' => 'В корзине нет товаров.'];
	}

	$connection = Application::getConnection();
	$sqlHelper = $connection->getSqlHelper();
	$fuserId = (int)\Bitrix\Sale\Fuser::getId(true);
	$count = $connection->query(
		'SELECT COUNT(*) C FROM miinox_shared_cart WHERE FUSER_ID=' . $fuserId . ' AND CREATED > DATE_SUB(NOW(), INTERVAL 1 DAY)'
	)->fetch();
	if ((int)($count['C'] ?? 0) >= 30) {
		return ['error' => 'Слишком много ссылок за сегодня. Используйте уже созданную.'];
	}

	$json = json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	if ($json === false) {
		return ['error' => 'Не удалось сохранить ссылку.'];
	}

	$code = '';
	for ($try = 0; $try < 5; $try++) {
		$candidate = miinoxSharedCartCode();
		$exists = $connection->query(
			"SELECT ID FROM miinox_shared_cart WHERE CODE='" . $sqlHelper->forSql($candidate) . "' LIMIT 1"
		)->fetch();
		if (!$exists) {
			$code = $candidate;
			break;
		}
	}
	if ($code === '') {
		return ['error' => 'Не удалось сохранить ссылку.'];
	}

	$connection->queryExecute(
		"INSERT INTO miinox_shared_cart (CODE, FUSER_ID, ITEMS, CREATED) VALUES ('"
		. $sqlHelper->forSql($code) . "', " . $fuserId . ", '"
		. $sqlHelper->forSql($json) . "', NOW())"
	);

	return ['url' => miinoxSharedCartUrl($code)];
}

function miinoxSharedCartAppliedCodes(): array
{
	$raw = (string)($_COOKIE['miinox_cart_shares'] ?? '');
	$codes = preg_split('/[^a-z0-9]+/', strtolower($raw), -1, PREG_SPLIT_NO_EMPTY);
	return array_values(array_unique($codes ?: []));
}

function miinoxSharedCartMarkApplied(string $code): void
{
	$codes = miinoxSharedCartAppliedCodes();
	$codes[] = $code;
	$codes = array_slice(array_values(array_unique($codes)), -30);
	$value = implode('.', $codes);
	$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
	setcookie('miinox_cart_shares', $value, [
		'expires' => time() + 86400 * 180,
		'path' => '/',
		'secure' => $secure,
		'httponly' => true,
		'samesite' => 'Lax',
	]);
	$_COOKIE['miinox_cart_shares'] = $value;
}

function miinoxApplySharedCart(string $code): string
{
	$code = strtolower((string)preg_replace('/[^a-zA-Z0-9]/', '', $code));
	if (strlen($code) < 6) {
		return 'Ссылка на корзину не найдена.';
	}
	if (!\Bitrix\Main\Loader::includeModule('sale') || !\Bitrix\Main\Loader::includeModule('catalog') || !miinoxSharedCartEnsureTable()) {
		return 'Не удалось открыть корзину по ссылке.';
	}

	$connection = Application::getConnection();
	$sqlHelper = $connection->getSqlHelper();
	$row = $connection->query(
		"SELECT ITEMS, FUSER_ID FROM miinox_shared_cart WHERE CODE='" . $sqlHelper->forSql($code) . "' LIMIT 1"
	)->fetch();
	if (!$row) {
		return 'Ссылка на корзину не найдена.';
	}

	$myFuser = (int)\Bitrix\Sale\Fuser::getId(true);
	if ((int)$row['FUSER_ID'] > 0 && (int)$row['FUSER_ID'] === $myFuser) {
		return 'Это ваша ссылка. В корзине текущий набор, он не задвоился.';
	}

	if (in_array($code, miinoxSharedCartAppliedCodes(), true)) {
		return 'Позиции из ссылки показаны отдельно от ваших.';
	}

	$items = json_decode((string)$row['ITEMS'], true);
	if (!is_array($items) || !$items) {
		return 'Ссылка на корзину пустая.';
	}

	$added = 0;
	$failed = 0;
	foreach ($items as $item) {
		if (!is_array($item)) {
			continue;
		}
		$productId = (int)($item['PRODUCT_ID'] ?? 0);
		$quantity = (float)str_replace(',', '.', (string)($item['QUANTITY'] ?? 0));
		if ($productId <= 0 || $quantity <= 0) {
			continue;
		}
		if ($quantity > 100000) {
			$quantity = 100000;
		}
		if (function_exists('ensureCatalogProductOrderable')) {
			ensureCatalogProductOrderable($productId, 40);
		}
		$props = [];
		if (!empty($item['PROPS']) && is_array($item['PROPS'])) {
			foreach ($item['PROPS'] as $prop) {
				if (!is_array($prop)) {
					continue;
				}
				$name = trim((string)($prop['NAME'] ?? ''));
				$value = trim((string)($prop['VALUE'] ?? ''));
				if ($name === '' || $value === '' || (string)($prop['CODE'] ?? '') === 'MIINOX_SHARE') {
					continue;
				}
				$props[] = [
					'NAME' => mb_substr($name, 0, 100),
					'CODE' => mb_substr((string)($prop['CODE'] ?? ''), 0, 50),
					'VALUE' => mb_substr($value, 0, 255),
					'SORT' => (int)($prop['SORT'] ?? 100),
				];
			}
		}
		$props[] = [
			'NAME' => 'По ссылке',
			'CODE' => 'MIINOX_SHARE',
			'VALUE' => $code,
			'SORT' => 1000,
		];
		$addResult = \Bitrix\Catalog\Product\Basket::addProduct([
			'PRODUCT_ID' => $productId,
			'QUANTITY' => $quantity,
			'PROPS' => $props,
		], [], ['USE_MERGE' => 'N']);
		if ($addResult->isSuccess()) {
			$added++;
		} else {
			$failed++;
		}
	}

	\Bitrix\Sale\BasketComponentHelper::updateFUserBasket($myFuser, SITE_ID);
	miinoxSharedCartMarkApplied($code);

	if ($added === 0) {
		return 'Не удалось добавить товары из ссылки.';
	}
	if ($failed > 0) {
		return 'По ссылке добавлены отдельные позиции — они под вашими товарами. Часть позиций из ссылки уже недоступна.';
	}
	return 'По ссылке добавлены отдельные позиции — они под вашими товарами.';
}

function miinoxSharedCartProductIds(string $code): array
{
	$code = strtolower((string)preg_replace('/[^a-zA-Z0-9]/', '', $code));
	if ($code === '' || !miinoxSharedCartEnsureTable()) {
		return [];
	}
	$connection = Application::getConnection();
	$sqlHelper = $connection->getSqlHelper();
	$row = $connection->query(
		"SELECT ITEMS, FUSER_ID FROM miinox_shared_cart WHERE CODE='" . $sqlHelper->forSql($code) . "' LIMIT 1"
	)->fetch();
	if (!$row) {
		return [];
	}
	if (\Bitrix\Main\Loader::includeModule('sale')) {
		$myFuser = (int)\Bitrix\Sale\Fuser::getId(true);
		if ((int)$row['FUSER_ID'] > 0 && (int)$row['FUSER_ID'] === $myFuser) {
			return [];
		}
	}
	$items = json_decode((string)$row['ITEMS'], true);
	if (!is_array($items)) {
		return [];
	}
	$ids = [];
	foreach ($items as $item) {
		if (!is_array($item)) {
			continue;
		}
		$productId = (int)($item['PRODUCT_ID'] ?? 0);
		if ($productId > 0) {
			$ids[$productId] = $productId;
		}
	}
	return array_values($ids);
}

function miinoxTagSharedBasketLines(string $code): array
{
	$ids = miinoxSharedCartProductIds($code);
	if (!$ids || !\Bitrix\Main\Loader::includeModule('sale')) {
		return $ids;
	}
	$code = strtolower((string)preg_replace('/[^a-zA-Z0-9]/', '', $code));
	$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(true), SITE_ID);
	$changed = false;
	foreach ($basket as $item) {
		if ($item->isDelay() || $item->getField('ORDER_ID') || !in_array((int)$item->getProductId(), $ids, true)) {
			continue;
		}
		$props = $item->getPropertyCollection();
		if (!$props) {
			continue;
		}
		$has = false;
		foreach ($props as $prop) {
			if ((string)$prop->getField('CODE') === 'MIINOX_SHARE') {
				$has = true;
				break;
			}
		}
		if ($has) {
			continue;
		}
		$propItem = $props->createItem();
		$propItem->setFields([
			'NAME' => 'По ссылке',
			'CODE' => 'MIINOX_SHARE',
			'VALUE' => $code,
			'SORT' => 1000,
		]);
		$changed = true;
	}
	if ($changed) {
		$basket->save();
	}
	return $ids;
}
