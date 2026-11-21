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
<div class="mi-photo-galery_section">
    <div class="container">
        <div class="row">
            <?foreach($arResult["ITEMS"] as $key => $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
                <div class="col-md-4 col-sm-6">
                <div class="mi-photo-galery_item">
                    <a href="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" class="fancybox" data-fancybox="group">
                        <img alt="<?=$arItem['NAME']?>" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>">
                    </a>
                </div>
            </div>
            <?endforeach;?>
        </div>
    </div>
</div>


