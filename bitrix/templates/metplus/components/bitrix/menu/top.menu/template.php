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
<ul class="head-menu">
<?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?>
    <li class="head-menu_item <? if(count($arColumns) > 0): ?>head-menu_catalog-item<?endif;?>">
        <a href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>">
            <span class="head-menu_text"><?=$arResult["ALL_ITEMS"][$itemID]["TEXT"]?></span>
        </a>
    <?if (is_array($arColumns) && count($arColumns) > 0):?>
        <div class="dropdown-content is-animation">
            <?foreach($arColumns as $key=>$arColumn):?>
                <ul class="dropdown-menu">
                    <? foreach ($arColumn as $nameKey => $arGroup): ?>
                        <? if (!is_array($arGroup) || empty($arGroup)) continue; ?>
                        <? foreach($arGroup as $itemIdLevel_2=>$arLevel_3): ?>
                            <? if (!isset($arResult["ALL_ITEMS"][$itemIdLevel_2])) continue; ?>
                            <li class="dropdown-menu_item<?=(is_array($arLevel_3) && count($arLevel_3) > 0) ? ' has-submenu' : ''?>">
                                <a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]?>">
                                    <?
                                    $menuPic = miinoxResolveSubcategoryImageSrc(
                                        '',
                                        (string)$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"],
                                        (string)$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]
                                    );
                                    if ($menuPic !== ''):
                                    ?>
                                    <span class="menu-photo"><img data-src="<?=htmlspecialcharsbx($menuPic)?>" alt=""></span>
                                    <? else:
                                        $icon = trim((string)($arResult["ALL_ITEMS"][$itemIdLevel_2]["PARAMS"]["ICON_MENU"] ?? ''));
                                        if ($icon === '' || strpos($icon, 'menu-icon_') === false) {
                                            $icon = miinoxResolveMenuIcon(
                                                (string)$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"],
                                                '',
                                                (string)$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]
                                            );
                                        }
                                    ?>
                                    <span class="menu-icon <?=$icon?>"></span>
                                    <? endif; ?>
                                    <span><?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?></span>
                                </a>
                                <?if (is_array($arLevel_3) && count($arLevel_3) > 0):?>
                                <div class="dropdown-submenu-content">
                                    <ul class="dropdown-submenu">
                                        <?foreach($arLevel_3 as $itemIdLevel_3):?>
                                            <? if (!isset($arResult["ALL_ITEMS"][$itemIdLevel_3])) continue; ?>
                                            <li>
                                                <a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["LINK"]?>">
                                                    <span><?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["TEXT"]?></span>
                                                </a>
                                            </li>
                                        <?endforeach;?>
                                    </ul>
                                    <?
                                    $pic = (string)($arResult["ALL_ITEMS"][$itemIdLevel_2]['PARAMS']['PICTURE'] ?? $arResult["ALL_ITEMS"][$itemIdLevel_2]['PARAMS']['picture_src'] ?? '');
                                    if ($pic !== ''):
                                    ?>
                                        <div class="dropdown-submenu_img">
                                            <img data-src="<?=htmlspecialcharsbx($pic)?>" alt="<?=htmlspecialcharsbx($arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"])?>">
                                        </div>
                                    <? endif; ?>
                                </div>
                                <?endif?>
                            </li>
                        <?endforeach;?>
                    <?endforeach;?>
                </ul>
            <?endforeach;?>
        </div>
    <?endif?>
    </li>
<?endforeach;?>
</ul>
