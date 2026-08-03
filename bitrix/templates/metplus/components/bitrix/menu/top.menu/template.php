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
?>
<ul class="head-menu">
<?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?>
    <?$existPictureDescColomn = ($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["picture_src"] || $arResult["ALL_ITEMS"][$itemID]["PARAMS"]["description"]) ? true : false;?>
    <li class="head-menu_item <? if(count($arColumns) > 0): ?>head-menu_catalog-item<?endif;?>">
        <a href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>">
            <span class="head-menu_text"><?=$arResult["ALL_ITEMS"][$itemID]["TEXT"]?></span>
        </a>
    <?if (is_array($arColumns) && count($arColumns) > 0):?>
        <div class="dropdown-content is-animation">
            <?foreach($arColumns as $key=>$arRow):?>
                <ul class="dropdown-menu">
                    <? foreach ($arRow as $nameKey => $arRow): ?>
                    <li class="dropdown-menu_item">
                        <a href="#" data-text="<?=($nameKey == 'in_stock') ? 'В наличии' : 'Под заказ';?>"></a>
                    </li>
                        <? foreach($arRow as $itemIdLevel_2=>$arLevel_3): ?>
                            <li class="dropdown-menu_item">

                                <a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]?>">
                                    <span class="menu-icon <?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["PARAMS"]["ICON_MENU"]?>"></span>
									<? if($arResult["IS_HIDDEN_MENU"]): ?>
										<span data-text="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?>"></span>
									<? else: ?>
										<span><?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?></span>
									<? endif; ?>
                                </a>
                            <?if (is_array($arLevel_3) && count($arLevel_3) > 0):?>
                            <div class="dropdown-submenu-content">
                                <ul class="dropdown-submenu">
                                    <?foreach($arLevel_3 as $itemIdLevel_3):?>
                                        <li>
                                            <a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["LINK"]?>">
                                                <? if($arResult["IS_HIDDEN_MENU"]): ?>
													<span data-text="<?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["TEXT"]?>"></span>
												<? else: ?>
													<span><?=$arResult["ALL_ITEMS"][$itemIdLevel_3]["TEXT"]?></span>
												<? endif; ?>
                                            </a>
                                        </li>
                                    <?endforeach;?>
                                </ul>
                                <? if(strlen($arResult["ALL_ITEMS"][$itemIdLevel_2]['PARAMS']['PICTURE']) > 0): ?>
                                    <div class="dropdown-submenu_img">
                                        <img data-src="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]['PARAMS']['PICTURE']?>" alt="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?>">
                                        <ul class="dropdown-submenu_img-text">
                                            <li>
                                                <small>Только у нас</small>
                                                <?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?>
                                            </li>
                                            <li>
                                                <small>по самой</small>
                                                выгодной <br>цене
                                            </li>
                                        </ul>
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
