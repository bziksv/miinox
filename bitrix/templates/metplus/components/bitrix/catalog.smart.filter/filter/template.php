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
if(!count($arResult["ITEMS"]))
    return false;
?>
<div class="mi-filter-box">
    <div class="mi-filter-box_title mi-is-active">
        Фильтр
    </div>

    <div class="filter-box_content" style="display:none;">

        <form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" style="display: contents;" class="smartfilter">
        <?foreach($arResult["HIDDEN"] as $arItem):?>
        <input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
        <?endforeach;?>

            <div class="row justify-content-between">
                <?foreach($arResult["ITEMS"] as $key=>$arItem)
                {
                    if(
                        empty($arItem["VALUES"])
                        || isset($arItem["PRICE"])
                    )
                        continue;

                    if (
                        $arItem["DISPLAY_TYPE"] == "A"
                        && (
                            $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
                        )
                    )
                        continue;
                    ?>

                    <?php
                    $arCur = current($arItem["VALUES"]);
                    switch ($arItem["DISPLAY_TYPE"]){
                        default:
                            ?>
                            <div class="mi-filter-box_column">
                                <div class="mi-filter-item">
                                    <span class="mi-filter-item_title"><?=$arItem["NAME"]?></span>
                                    <select class="js-select" name="<?=$arCur["CONTROL_NAME_ALT"]?>" onchange="smartFilter.keyup(this)">
                                        <option value=""><?=$arItem["NAME"]?></option>
                                        <? foreach ($arItem["VALUES"] as $arValue): ?>
                                            <option value="<?=$arValue["HTML_VALUE_ALT"]?>" id="<?=$arValue["CONTROL_ID"]?>" <?=($arValue["CHECKED"]) ? 'selected' : null;?>><?=$arValue["VALUE"]?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <?
                            ?>
                    <?php } ?>
                <?} ?>
            </div>

            <div class="mi-filter-box_footer" id="modef">
                <a href="<?echo $arResult["FILTER_URL"]?>" class="mi-main-btn mi-filter_search-btn"><span id="modef_num">Искать</span></a>
                <a href="?del_filter=Сбросить" class="mi-filter_reset-btn"><span class="glipf-reset"></span>Сбросить фильтр</a>
            </div>
        </form>
    </div>
</div>

<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', 'horizontal');
</script>