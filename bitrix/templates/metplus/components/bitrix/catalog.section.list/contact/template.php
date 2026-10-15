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
<div class="contact-section">
    <div class="container">
        <div class="row">
            <div class="contact_left-column">
                <div class="title-h3"><?=$arResult['BLOCK']['NAME']?></div>
                <div class="tab-container">
                    <ul class="tab-names">
                        <? foreach ($arResult['SECTIONS'] as $key => $arSection):
                        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
                        ?>
                            <li class="tab <?if($key === $arResult['DEFAULT_CITY_KEY']):?>active<?endif;?>"><span class="tab-text"><?=$arSection['NAME']?></span></li>
                        <?endforeach;?>
                    </ul>
                    <div class="content-tabs">
                        <? foreach ($arResult['SECTIONS'] as $key => $arSection):
                        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
                        ?>
                        <div class="tab-item <?if($key === $arResult['DEFAULT_CITY_KEY']):?>is-visible<?endif;?>">
                            <div class="wrapper_select-office">
                                <select class="js-select">
                                    <? foreach ($arSection['ITEMS'] as $arItem):?>
                                        <option value="<?=$arItem['ID']?>" data-center="<?=$arItem['PROPERTIES']['CENTER']['VALUE']?>"<?=!empty($arItem['IS']) ? ' selected' : ''?>><?=$arItem['NAME']?></option>
                                    <?endforeach;?>
                                </select>
                            </div>
                            <? foreach ($arSection['ITEMS'] as $k => $arItem):?>
                                <div class="contact-section-desc <?if($arItem['IS']):?>is-active<?endif;?>">
                                <div class="contact-section_subtitle"><?=$arItem['NAME']?></div>
                                <div class="contact-section_adress">
                                    <span class="glipf-place-2"></span><?=$arItem['PROPERTIES']['STREET']['VALUE']?>
                                </div>
                                <div class="contact-section_subtitle"><?=$arItem['PROPERTIES']['WORK']['NAME']?></div>
                                <ul class="contact-section_list contact-section_list_mod">
                                    <? foreach ($arItem['PROPERTIES']['WORK']['VALUE'] as $work):?>
                                    <li>
                                        <span class="contact-icon glipf-clock-2"></span><?=$work?>
                                    </li>
                                    <?endforeach;?>
                                </ul>
                                <div class="row">
                                    <? foreach ($arItem['PROPERTIES']['MANAGER']['~VALUE'] as $m => $man):?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="contact-section_subtitle"><?=$arItem['PROPERTIES']['MANAGER']['DESCRIPTION'][$m]?></div>
                                        <ul class="contact-section_list">
                                            <?=$man;?>
                                        </ul>
                                    </div>
                                    <?endforeach;?>
                                </div>
                                <div class="row">
                                    <? if($arItem['PROPERTIES']['PRICE']['VALUE']):?>
                                    <div class="col-sm-6">
                                        <a href="<?=CFile::GetPath($arItem['PROPERTIES']['PRICE']['VALUE'])?>" class="driving-directions_btn main-btn" download>
                                            <span class="glipf-download"></span><?=$arItem['PROPERTIES']['PRICE']['NAME']?>
                                        </a>
                                    </div>
                                    <? endif;?>

                                    <? if($arItem['PROPERTIES']['DOC']['VALUE']):?>
                                    <div class="col-sm-6">
                                        <a href="<?=CFile::GetPath($arItem['PROPERTIES']['DOC']['VALUE'])?>" class="download-details_btn" download>
                                            <span class="glipf-pdf"></span>
                                            <?=$arItem['PROPERTIES']['DOC']['NAME']?>
                                        </a>
                                    </div>
                                    <? endif;?>
                                </div>
                            </div>
                            <?endforeach;?>
                        </div>
                        <?endforeach;?>
                    </div>
                </div>
            </div>
            <div class="contact_right-column">
                <? foreach ($arResult['SECTIONS'] as $key => $arSection):
                $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
                ?>
                <div class="contact-img tab-item <?if($key === $arResult['DEFAULT_CITY_KEY']):?>is-visible<?endif;?>">
                    <? foreach ($arSection['ITEMS'] as $k => $arItem): ?>
                        <?if($arItem['PREVIEW_PICTURE']):?>
                            <img class="contact-section-desc <?if($arItem['IS']):?>is-active<?endif;?>" data-src="<?=$arItem['PREVIEW_PICTURE']?>" alt="<?=$arItem['NAME']?>">
                        <?else:?>
                            <img class="contact-section-desc <?if($arItem['IS']):?>is-active<?endif;?>" data-src="<?=CFile::GetPath($arResult['BLOCK']['PICTURE'])?>" alt="<?=$arItem['NAME']?>">
                        <?endif;?>
                    <?endforeach;?>
                </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
</div>




