<?
/**
 * @global CMain $APPLICATION
 * @var string $arItem
 * @var string $priceGroup
 *
 */
?>
<div class="product-item_popup">
    <div class="product-item_popup-close"><span class="glipf-reset"></span></div>
    <ul class="product-item_popup-list">
        <li>
            <strong>Наименование товара</strong>
            <span class="product-item_name"><?=($arItem['PROPERTIES']['SEO_NAME']['VALUE']) ? $arItem['PROPERTIES']['SEO_NAME']['VALUE'] : htmlspecialchars_decode(preg_replace(array('|[\s]+|s','/\(|\)/'), array(' ', '"'), trim($arItem['NAME'])))?></span>
        </li>
        <li>
            <strong>Марка Стали</strong>
            <?=$arItem['PROPERTIES']['TYPE_METALL']['VALUE']?>
        </li>
        <li>
            <strong><?=(isset($arResult['FIELDS'][2])) ? $arResult['FIELDS'][2] : 'Вес'?></strong>
            <?=$arItem['PROPERTIES']['_3_VESPMSAYT']['VALUE']?>
        </li>
        <li>
            <strong>Цена руб/кг (с НДС)</strong>
            <?=$priceGroup?>
        </li>
        <li>
            <strong>Резка, руб</strong>
            <?=CurrencyFormat($arItem['PROPERTIES']['PRICE_CUTTING']['VALUE'], $arItem['ITEM_PRICES'][0]['CURRENCY']);?>
        </li>
    </ul>

    <a href="javascript:void(0)" class="main-btn product-item_buy-btn">Купить</a>
</div>
