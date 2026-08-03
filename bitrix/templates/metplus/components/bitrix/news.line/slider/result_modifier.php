<?


foreach($arResult["ITEMS"] as &$arItem) {
	$db_props = CIBlockElement::GetProperty($arItem["IBLOCK_ID"], $arItem["ID"], [], []);
	
	while ($ar_props = $db_props->GetNext()) {
		$arItem["PROPERTIES"][$ar_props["CODE"]] = $ar_props;
	}
}