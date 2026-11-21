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
<div class="mi-footer-column">
    <div class="row">
        <?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?>
        <?$existPictureDescColomn = ($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["picture_src"] || $arResult["ALL_ITEMS"][$itemID]["PARAMS"]["description"]) ? true : false;?>
        <div class="col-lg-4 col-md-6 col-6">
            <div class="mi-footer-title">
                <a href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>">
                    <?=$arResult["ALL_ITEMS"][$itemID]["TEXT"]?>
                </a>
            </div>
            <?foreach($arColumns as $key=>$arRow):?>
            <ul class="mi-footer-menu">
                <?foreach($arRow as $itemIdLevel_2=>$arLevel_3):?>
                <li>
                    <a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]?>">
                        <?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"]?>
                    </a>
                </li>
                <?endforeach;?>
            </ul>
            <?endforeach;?>
        </div>
        <?endforeach;?>
    </div>
</div>

