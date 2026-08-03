<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;
$aMenuLinksExt = array();

if(CModule::IncludeModule('iblock'))
{
	$arFilter = array(
		"TYPE" => "1c_catalog",
		"SITE_ID" => SITE_ID,
	);

	$dbIBlock = CIBlock::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), $arFilter);
	$dbIBlock = new CIBlockResult($dbIBlock);

	if ($arIBlock = $dbIBlock->GetNext())
	{
		if(defined("BX_COMP_MANAGED_CACHE"))
			$GLOBALS["CACHE_MANAGER"]->RegisterTag("iblock_id_".$arIBlock["ID"]);

		if($arIBlock["ACTIVE"] == "Y")
		{
			$aMenuLinksExt = $APPLICATION->IncludeComponent("prime:menu.sections", "bootstrap_v4", array(
				"IS_SEF" => "Y",
				"SEF_BASE_URL" => "/catalog/",
				"SECTION_PAGE_URL" => "#SECTION_CODE#/",
				"DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_CODE#/",
				"IBLOCK_TYPE" => $arIBlock['IBLOCK_TYPE_ID'],
				"IBLOCK_ID" => $arIBlock['ID'],
				"DEPTH_LEVEL" => "3",
				"CACHE_TYPE" => "N",
			), false, Array('HIDE_ICONS' => 'Y'));

			// Один корневой раздел 1С — в выпадающем меню показываем его детей как пункты первого уровня
			$filtered = array();
			foreach ($aMenuLinksExt as $link)
			{
				$depth = intval($link[3]["DEPTH_LEVEL"]);
				if ($depth < 2)
					continue;
				$link[3]["DEPTH_LEVEL"] = $depth - 1;
				$filtered[] = $link;
			}
			if (!empty($filtered))
				$aMenuLinksExt = $filtered;

			require_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/catalog_icons.php";
			miinoxApplyMenuIconFallbacks($aMenuLinksExt);
		}
	}

	if(defined("BX_COMP_MANAGED_CACHE"))
		$GLOBALS["CACHE_MANAGER"]->RegisterTag("iblock_id_new");
}

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>
