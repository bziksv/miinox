<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

header('Content-Type: application/json; charset=UTF-8');

if (!check_bitrix_sessid()) {
	echo json_encode(['error' => 'Сессия устарела. Обновите страницу.'], JSON_UNESCAPED_UNICODE);
	return;
}

$result = function_exists('miinoxCreateSharedCart')
	? miinoxCreateSharedCart()
	: ['error' => 'Не удалось сохранить ссылку.'];

echo json_encode($result, JSON_UNESCAPED_UNICODE);
