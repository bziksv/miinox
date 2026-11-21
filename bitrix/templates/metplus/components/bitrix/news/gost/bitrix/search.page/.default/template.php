<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
?>
<div class="row mi-wrapper_gost-item">
    <?if(count($arResult["SEARCH"])>0):?>
        <?foreach($arResult["SEARCH"] as $arItem):?>
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                <div class="mi-gost-item">
                    <a href="<?echo CFile::GetPath($arItem["PROPERTIES"]["FILE"]["VALUE"])?>" download>
                        <span class="mi-gost-item_icon glipf-pdf"></span>
                        <div class="mi-gost-item-desc">
                            <p><?echo $arItem["TITLE_FORMATED"]?></p>
                        </div>
                    </a>
                </div>
            </div>
        <?endforeach;?>
    <?else:?>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
        <div class="mi-gost-item"><?=GetMessage("SEARCH_NOTHING_TO_FOUND")?></div>
    </div>
    <?endif;?>
</div>
