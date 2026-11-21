<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
<div class="mi-order-transport_section">
    <div class="container">
        <div class="mi-title-h3"><?=$arResult['BLOCK']['NAME']?></div>
        <div class="row">
            <?foreach($arResult["ITEMS"] as $key => $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="col-md-6 mi-order-transport_column">
                <div class="mi-order-transport_item">
                    <div class="mi-order-transport_item-img">
                        <img data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
                    </div>
                    <span class="mi-order-transport_item-title"><?=$arItem['NAME']?></span>
                    <a href="<?=$arItem['CODE']?>" class="mi-main-btn mi-order-transport_item-btn">Заказать</a>
                </div>
            </div>
            <?endforeach;?>
        </div>
    </div>
</div>

