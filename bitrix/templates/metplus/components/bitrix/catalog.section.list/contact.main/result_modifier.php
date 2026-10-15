<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$res = CIBlock::GetByID($arParams['IBLOCK_ID']);
if($ar_res = $res->GetNext())
    $arResult['BLOCK'] = $ar_res;

foreach ($arResult['SECTIONS'] as $key => &$arSection){
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
    $arFilter = Array("IBLOCK_ID" => $arSection['IBLOCK_ID'], "ACTIVE" => "Y", "SECTION_ID" => $arSection['ID']);

    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement()){
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        $arSection['ITEMS'][$arFields['ID']] = $arFields;
        $arSection['ITEMS'][$arFields['ID']]['PROPERTIES'] = $arProps;

        $arResult['ITEMS'][$arFields['ID']] = $arSection['ITEMS'][$arFields['ID']];
    }
}
unset($arSection);

$defaultId = null;
foreach ($arResult['ITEMS'] as $id => $item) {
    if (mb_stripos((string)$item['NAME'], 'Воронеж') !== false) {
        $defaultId = $id;
        break;
    }
}
if ($defaultId === null && !empty($arResult['ITEMS'])) {
    $defaultId = key($arResult['ITEMS']);
}
if ($defaultId !== null) {
    $arResult['ITEMS'][$defaultId]['IS'] = true;
    foreach ($arResult['SECTIONS'] as &$arSection) {
        if (!empty($arSection['ITEMS'][$defaultId])) {
            $arSection['ITEMS'][$defaultId]['IS'] = true;
        }
    }
    unset($arSection);
}
?>