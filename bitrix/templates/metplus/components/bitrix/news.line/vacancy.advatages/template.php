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
<div class="mi-title-h3"><?=$arResult['BLOCK']['NAME']?></div>
<ul class="mi-vacancy-section_advatages-list">
    <?foreach($arResult["ITEMS"] as $key => $arItem): ?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <li>
        <span class="mi-advatages-icon <?=$arItem['CODE']?>"></span>
        <span class="mi-advatages-title"><?=$arItem['~NAME']?></span>
        <p><?=$arItem['PREVIEW_TEXT']?></p>
    </li>
    <?endforeach;?>
</ul>

