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


<? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
<div class="row mi-services-detailed_box">
    <div class="mi-left-column <? if($key % 2): ?>mi-order-md-2<? endif;?>">
        <div class="mi-services-detailed_img">
            <img data-src="<?=$arItem['IMG']?>" alt="<?=$arResult['NAME']?>">
        </div>
    </div>
    <div class="mi-right-column"><?=$arItem['TEXT']?></div>
</div>
<?endforeach;?>

<div class="mi-services-detailed_footer">
    <span class="text">Узнайте стоимость всех услуг</span>
    <a href="<?=CFile::GetPath($arResult['PROPERTIES']['PRICE']['VALUE'])?>" class="mi-download-price_btn mi-main-btn" download><span class="glipf-download"></span>Скачать прайс</a>
</div>