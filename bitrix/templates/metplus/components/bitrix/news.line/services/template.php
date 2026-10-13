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
<div class="services-section" id="home-services" data-svc-layout="bands" data-hd-theme="paper">
    <div class="container">
        <div class="section-title">УСЛУГИ</div>
        <div class="services-grid">
            <?foreach($arResult["ITEMS"] as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <div class="services-grid__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <div class="service-item">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="service-item_mobile-link"></a>
                        <img data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
                        <div class="service-item_desc">
                            <span class="service-item_title"><?=$arItem['NAME']?></span>
                            <div class="service-item_desc-body">
                                <p><?=$arItem['PREVIEW_TEXT']?></p>
                                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="service-item_btn">Подробнее</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
</div>
