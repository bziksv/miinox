<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$res = CIBlock::GetByID($arParams['IBLOCK_ID']);
if($ar_res = $res->GetNext())
    $arResult['BLOCK'] = $ar_res;

$defaultCityKey = 0;
foreach ($arResult['SECTIONS'] as $key => $section) {
    if (mb_stripos((string)$section['NAME'], 'Воронеж') !== false) {
        $defaultCityKey = $key;
        break;
    }
}
$arResult['DEFAULT_CITY_KEY'] = $defaultCityKey;

foreach ($arResult['SECTIONS'] as $key => &$arSection){
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_PICTURE", "DATE_ACTIVE_FROM","PROPERTY_*");
    $arFilter = Array("IBLOCK_ID" => $arSection['IBLOCK_ID'], "ACTIVE" => "Y", "SECTION_ID" => $arSection['ID']);
    $is = ($key === $defaultCityKey);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement()){
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        $arSection['ITEMS'][$arFields['ID']] = $arFields;
        $arSection['ITEMS'][$arFields['ID']]['PREVIEW_PICTURE'] = CFile::GetPath($arFields['PREVIEW_PICTURE']);
        $arSection['ITEMS'][$arFields['ID']]['IS'] = $is;
        $arSection['ITEMS'][$arFields['ID']]['PROPERTIES'] = $arProps;
        $is = false;
    }
}
unset($arSection);
?>