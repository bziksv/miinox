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

<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
<h1 class="mi-news-detailed_title" style="color:#2b2b2b"><?=$arResult["NAME"]?></h1>
<?endif;?>

<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
    <span class="mi-news-detailed_date"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
<?endif;?>
<p><?echo $arResult["DETAIL_TEXT"];?></p>

<?foreach($arResult['IMAGES'] as $img):?>
<figure class="mi-news-detailed_img">
    <img data-src="<?=$img?>" alt="<?=$arResult["NAME"]?>">
</figure>
<?endforeach;?>
