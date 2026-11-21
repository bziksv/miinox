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
<div class="mi-catalog-section">
    <div class="container">
        <div class="row">
            <? if(count($arResult["SEARCH"])>0):?>
            <div class="mi-catalog-section-header">
                <div class="mi-catalog-product_text-block">
                    <div class="mi-title-h3">Поиск: <?=$arResult['REQUEST']['QUERY'];?></div>
                    <?if($arResult["REQUEST"]["HOW"]=="d"):?>
                        <a href="<?=$arResult["URL"]?>&amp;how=r<?echo $arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?echo $arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=GetMessage("SEARCH_SORT_BY_RANK")?></a>&nbsp;|&nbsp;<b><?=GetMessage("SEARCH_SORTED_BY_DATE")?></b>
                    <?else:?>
                        <b><?=GetMessage("SEARCH_SORTED_BY_RANK")?></b>&nbsp;|&nbsp;<a href="<?=$arResult["URL"]?>&amp;how=d<?echo $arResult["REQUEST"]["FROM"]? '&amp;from='.$arResult["REQUEST"]["FROM"]: ''?><?echo $arResult["REQUEST"]["TO"]? '&amp;to='.$arResult["REQUEST"]["TO"]: ''?>"><?=GetMessage("SEARCH_SORT_BY_DATE")?></a>
                    <?endif;?>
                </div>
            </div>
            <table class="mi-product-table">
                <tr>
                    <th>
                        Наименование товара, <span class="min">размер (мм)</span>
                    </th>
                    <th>Марка Стали</th>
                    <th>Вес пм</th>
                    <th>Цена руб/кг <span class="min">(с НДС)</span></th>
                    <th>Порезка, руб</th>
                    <th>Купить</th>
                </tr>
                <tr><td colspan="6" class="mi-product-table_form-search"></td></tr>

                <?foreach($arResult["SEARCH"] as $arItem): ?>
                <tr>
                    <td class="mi-product-table_first-cell">
                        <span class="mi-product-item_name <? if($arItem['CATALOG_QUANTITY'] < 1000):?>product-item_name-mod<?endif;?>"><?echo $arItem["TITLE_FORMATED"]?></span>
                        <span class="mi-product-availability">
                            <? if($arItem['CATALOG_QUANTITY'] < 1000):?>Количество ограничено, уточняйте у менеджера<? else:?>В наличии на складе.<?endif;?>
                        </span>
                    </td>
                    <td><?=$arItem['TYPE_METALL']['VALUE']?></td>
                    <td><?=$arItem['_3_VESPMSAYT']['VALUE']?></td>
                    <td><?=$arItem['PRICE']?></td>
                    <td><?=$arItem['PRICE_CUTTING']['VALUE']?></td>
                    <td>
                        <a href="javascript:void(0)" class="mi-product-item_cart-btn mi-main-btn" id="<?=$arItem['ITEM_ID']?>"><span class="glipf-cart"></span></a>
                    </td>
                </tr>
                <?endforeach;?>
            </table>
            <div class="row">
                <div class="col-md-12">
                    <?=$arResult["NAV_STRING"]?>
                </div>
            </div>
            <? else:?>
                <div class="mi-catalog-section-header">
                    <div class="mi-catalog-product_text-block">
                        <div class="mi-title-h3"><?ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND"));?></div>
                    </div>
                </div>
            <? endif;?>
        </div>
    </div>
</div>