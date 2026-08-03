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
<div class="main-section" <? if($arResult["ITEMS"][0]['PREVIEW_PICTURE']['SRC']): ?>style="background: url(<?=$arResult["ITEMS"][0]['PREVIEW_PICTURE']['SRC']?>) no-repeat center top;"<? endif;?>>
    <div class="container">
        <div class="main-slider">
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="main-slide" data-background="<?=$arItem['PREVIEW_PICTURE']['SRC'];?>">
					<div class="slider-title">
						<?=$arItem["PROPERTIES"]["TITLE"]["VALUE"]?>
					</div>
					<div class="slider-subtitle"><?=$arItem["PROPERTIES"]["SUBTITLE"]["VALUE"]?></div>
					
					<div class="slider-text"><?=$arItem['PREVIEW_TEXT']?></div>
                    <a href="<?echo $arItem["CODE"]?>" class="main-btn main-section_catalog-btn"><?echo $arItem["NAME"]?></a>
                </div>
            <?endforeach;?>
        </div>
    </div>
</div>



