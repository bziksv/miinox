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

require_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/catalog_icons.php";

/**
 * Иконка/фото для пункта L2.
 */
$renderMenuItemMedia = static function (array $item) {
	$menuPic = miinoxResolveSubcategoryImageSrc(
		'',
		(string)$item["TEXT"],
		(string)$item["LINK"]
	);
	if ($menuPic !== '') {
		?>
		<span class="menu-photo"><img data-src="<?=htmlspecialcharsbx($menuPic)?>" alt=""></span>
		<?php
		return;
	}
	$icon = trim((string)($item["PARAMS"]["ICON_MENU"] ?? ''));
	if ($icon === '' || strpos($icon, 'menu-icon_') === false) {
		$icon = miinoxResolveMenuIcon(
			(string)$item["TEXT"],
			'',
			(string)$item["LINK"]
		);
	}
	?>
	<span class="menu-icon <?=htmlspecialcharsbx($icon)?>"></span>
	<?php
};

$resolveWorldTheme = static function (array $world) {
	$nameLower = mb_strtolower((string)$world["TEXT"]);
	$linkLower = mb_strtolower((string)$world["LINK"]);
	if (
		strpos($nameLower, 'цвет') !== false
		|| strpos($linkLower, 'tsvet') !== false
		|| strpos($linkLower, 'cvet') !== false
		|| strpos($linkLower, 'цвет') !== false
	) {
		return 'copper';
	}
	return 'steel';
};
?>
<div class="fixed-menu_catalog fixed-menu_catalog--tree">
    <a href="/#katalog" class="fixed-panel_catalog-btn"><span class="head-menu_text">КАТАЛОГ</span></a>
    <div class="dropdown-content">
        <ul class="dropdown-menu">
            <?foreach($arResult["MENU_STRUCTURE"] as $nameKey => $arColumns): ?>
                <? if (!is_array($arColumns) || empty($arColumns)) continue; ?>
                <? foreach ($arColumns as $itemID => $arItemColumns): ?>
                    <? if (!isset($arResult["ALL_ITEMS"][$itemID])) continue; ?>
                    <?
                    // result_modifier: [1 => [childId => grandchildIds[]]] or [childId => grandchildIds[]]
                    $children = [];
                    if (is_array($arItemColumns)) {
                        if (isset($arItemColumns[1]) && is_array($arItemColumns[1])) {
                            $children = $arItemColumns[1];
                        } else {
                            $children = $arItemColumns;
                        }
                    }
                    $childIds = [];
                    foreach ($children as $childId => $level3) {
                        if (isset($arResult["ALL_ITEMS"][$childId])) {
                            $childIds[] = $childId;
                        }
                    }
                    $world = $arResult["ALL_ITEMS"][$itemID];
                    $theme = $resolveWorldTheme($world);
                    ?>
                    <li class="dropdown-menu_world dropdown-menu_world--<?=$theme?>">
                        <a class="dropdown-menu_world__title" href="<?=$world["LINK"]?>">
                            <span><?=$world["TEXT"]?></span>
                        </a>
                        <? if ($childIds): ?>
                        <ul class="dropdown-menu_world__list">
                            <? foreach ($childIds as $childId): ?>
                                <? $child = $arResult["ALL_ITEMS"][$childId]; ?>
                                <li class="dropdown-menu_item">
                                    <a href="<?=$child["LINK"]?>">
                                        <? $renderMenuItemMedia($child); ?>
                                        <span><?=$child["TEXT"]?></span>
                                    </a>
                                </li>
                            <? endforeach; ?>
                        </ul>
                        <? endif; ?>
                    </li>
                <?endforeach;?>
            <?endforeach;?>
        </ul>
    </div>
</div>
