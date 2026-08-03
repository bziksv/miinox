<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

$jsHiddenIds = [];
if (!empty($arResult['PROPERTIES']['UF_JS_MENU']) && is_array($arResult['PROPERTIES']['UF_JS_MENU'])) {
	$jsHiddenIds = $arResult['PROPERTIES']['UF_JS_MENU'];
}

$isJsHidden = static function ($itemId) use ($jsHiddenIds) {
	return $itemId !== null && $itemId !== '' && in_array($itemId, $jsHiddenIds, false);
};

require_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/catalog_icons.php";
?>
<ul class="catalog-menu">
<?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?>
	<? if (!isset($arResult["ALL_ITEMS"][$itemID])) continue; ?>
	<?
	$item = $arResult["ALL_ITEMS"][$itemID];
	$menuPic = miinoxResolveSubcategoryImageSrc('', (string)$item["TEXT"], (string)$item["LINK"]);
	$icon = '';
	if ($menuPic === '') {
		$icon = trim((string)($item["PARAMS"]["ICON_MENU"] ?? ''));
		if ($icon === '' || strpos($icon, 'menu-icon_') === false) {
			$icon = miinoxResolveMenuIcon((string)$item["TEXT"], '', (string)$item["LINK"]);
		}
	}
	$itemDbId = $item["PARAMS"]["ID"] ?? null;
	?>
    <li class="catalog-menu_item">
        <a href="<?=$item["LINK"]?>">
            <? if ($menuPic !== ''): ?>
            <span class="menu-photo"><img src="<?=htmlspecialcharsbx($menuPic)?>?v=norm2" alt="" loading="lazy"></span>
            <? else: ?>
            <span class="menu-icon <?=$icon?>"></span>
            <? endif; ?>
            <? if ($isJsHidden($itemDbId)): ?>
                <span data-text="<?=mb_strtoupper($item["TEXT"])?>"></span>
            <? else: ?>
                <span><?=mb_strtoupper($item["TEXT"])?></span>
            <? endif; ?>
        </a>
    <?if (is_array($arColumns) && count($arColumns) > 0):?>
        <?foreach($arColumns as $key=>$arRow):?>
			<? if (!is_array($arRow) || empty($arRow)) continue; ?>
            <ul class="catalog-submenu">
            <?foreach($arRow as $itemIdLevel_2=>$arLevel_3):?>
				<? if (!isset($arResult["ALL_ITEMS"][$itemIdLevel_2])) continue; ?>
				<?
				$child = $arResult["ALL_ITEMS"][$itemIdLevel_2];
				$childDbId = $child["PARAMS"]["ID"] ?? null;
				?>
                <li>
                    <a href="<?=$child["LINK"]?>">
                        <? if ($isJsHidden($childDbId)): ?>
                            <span data-text="<?=$child["TEXT"]?>"></span>
                        <? else: ?>
                            <span><?=$child["TEXT"]?></span>
                        <? endif; ?>
                    </a>
                </li>
            <?endforeach;?>
            </ul>
        <?endforeach;?>
    <?endif?>
    </li>
<?endforeach;?>
</ul>
