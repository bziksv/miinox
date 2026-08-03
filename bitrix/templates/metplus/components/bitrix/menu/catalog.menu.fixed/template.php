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
?>
<div class="fixed-menu_catalog">
    <a href="/catalog/" class="fixed-panel_catalog-btn"><span class="head-menu_text">КАТАЛОГ</span></a>
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
                    $hasSub = count($childIds) > 0;
                    ?>
                    <li class="dropdown-menu_item<?=$hasSub ? ' has-submenu' : ''?>">
                        <a href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>">
                            <?
                            $menuPic = miinoxResolveSubcategoryImageSrc(
                                '',
                                (string)$arResult["ALL_ITEMS"][$itemID]["TEXT"],
                                (string)$arResult["ALL_ITEMS"][$itemID]["LINK"]
                            );
                            if ($menuPic !== ''):
                            ?>
                            <span class="menu-photo"><img data-src="<?=htmlspecialcharsbx($menuPic)?>" alt=""></span>
                            <? else:
                                $icon = trim((string)($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["ICON_MENU"] ?? ''));
                                if ($icon === '' || strpos($icon, 'menu-icon_') === false) {
                                    $icon = miinoxResolveMenuIcon(
                                        (string)$arResult["ALL_ITEMS"][$itemID]["TEXT"],
                                        '',
                                        (string)$arResult["ALL_ITEMS"][$itemID]["LINK"]
                                    );
                                }
                            ?>
                            <span class="menu-icon <?=$icon?>"></span>
                            <? endif; ?>
                            <span><?=$arResult["ALL_ITEMS"][$itemID]["TEXT"]?></span>
                        </a>
                        <? if ($hasSub): ?>
                        <div class="dropdown-submenu-content">
                            <ul class="dropdown-submenu">
                                <? foreach ($childIds as $childId): ?>
                                    <li>
                                        <a href="<?=$arResult["ALL_ITEMS"][$childId]["LINK"]?>">
                                            <span><?=$arResult["ALL_ITEMS"][$childId]["TEXT"]?></span>
                                        </a>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                        <? endif; ?>
                    </li>
                <?endforeach;?>
            <?endforeach;?>
        </ul>
    </div>
</div>
