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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>
<div class="map-contact_box">
    <div class="section-title">КОНТАКТЫ</div>
    <select class="js-select">
        <? foreach ($arResult['SECTIONS'] as $key => $arSection):
        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
        ?>
        <optgroup label="<?=$arSection['NAME']?>">
            <? foreach ($arSection['ITEMS'] as $arItem):?>
            <option value="<?=$arItem['ID']?>" data-center="<?=$arItem['PROPERTIES']['CENTER']['VALUE']?>"<?=!empty($arItem['IS']) ? ' selected' : ''?>><?=$arItem['NAME']?></option>
            <?endforeach;?>
        </optgroup>
        <?endforeach;?>
    </select>

    <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
    <ul class="map-contact_list" data-id="<?=$arItem['ID']?>" <? if(!isset($arItem['IS'])):?>style="display: none;"<?endif;?>>
        <? if($arItem['PROPERTIES']['STREET']['VALUE']): ?>
        <li><span class="contact-icon glipf-place-2"></span><?=$arItem['PROPERTIES']['STREET']['VALUE']?></li>
        <? endif; ?>
        <? if($arItem['PROPERTIES']['PHONE_MAIN']['VALUE']): ?>
        <li><a href="tel:<?=$arItem['PROPERTIES']['PHONE_MAIN']['VALUE']?>"><span class="contact-icon glipf-call-answer"></span><?=$arItem['PROPERTIES']['PHONE_MAIN']['VALUE']?> </a></li>
        <? endif; ?>

        <? if($arItem['PROPERTIES']['MAIL_MAIN']['VALUE']): ?>
        <li><a href="mailto:<?=$arItem['PROPERTIES']['MAIL_MAIN']['VALUE']?>" class="map-contact_mail"><span class="contact-icon glipf-email"></span><?=$arItem['PROPERTIES']['MAIL_MAIN']['VALUE']?></a></li>
        <? endif; ?>
    </ul>
    <?endforeach;?>
    <!--<a href="#callback" class="main-btn map-callback_btn" data-toggle="modal"><span class="glipf-call-answer"></span>Заказать обратный звонок</a>-->
</div>




