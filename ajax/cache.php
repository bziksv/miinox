<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$staticHtmlCache = \Bitrix\Main\Data\StaticHtmlCache::getInstance();
$staticHtmlCache->deleteAll();