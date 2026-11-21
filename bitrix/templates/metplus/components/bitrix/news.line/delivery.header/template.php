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
<div class="row mi-delivery-section_header">
    <div class="col-xl-5 col-lg-6">
        <div class="mi-title-h3"><?=$arResult['BLOCK']['NAME']?></div>
        <p class="mi-top-text">
            <?=$arResult['BLOCK']['DESCRIPTION']?>
        </p>
        <div class="row">
            <?foreach($arResult["ITEMS"] as $key => $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="col-sm-6 mi-delivery-column">
                <div class="mi-delivery-item">
                    <span class="mi-delivery-item_icon <?=$arItem['CODE']?>"></span>
                    <div class="mi-delivery-item_title"><?=$arItem['NAME']?></div>
                    <p><?=$arItem['PREVIEW_TEXT']?></p>
                </div>
            </div>
            <?endforeach;?>
        </div>
    </div>
    <div class="col-xl-7 col-lg-6">
        <div class="mi-delivery-img">
            <img data-src="<?=CFile::GetPath($arResult['BLOCK']['PICTURE'])?>" alt="<?=$arResult['BLOCK']['NAME']?>">
        </div>
    </div>
</div>

