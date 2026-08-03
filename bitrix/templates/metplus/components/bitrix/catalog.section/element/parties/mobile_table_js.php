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
            <strong data-text="Наименование товара"></strong>
            <span class="product-item_name" data-text="<?=($arItem['PROPERTIES']['SEO_NAME']['VALUE']) ? $arItem['PROPERTIES']['SEO_NAME']['VALUE'] : htmlspecialchars_decode(preg_replace(array('|[\s]+|s','/\(|\)/'), array(' ', '"'), trim($arItem['NAME'])))?>"></span>
        </li>
        <li>
            <strong data-text="Марка Стали"></strong>
            <span data-text="<?=$arItem['PROPERTIES']['TYPE_METALL']['VALUE']?>"></span>
        </li>
        <li>
            <strong data-text="<?=(isset($arResult['FIELDS'][2])) ? $arResult['FIELDS'][2] : 'Вес'?>"></strong>
            <span data-text="<?=$arItem['PROPERTIES']['_3_VESPMSAYT']['VALUE']?>"></span>
        </li>
        <li>
            <strong data-text="Цена руб/кг (с НДС)"></strong>
            <span data-text="<?=$priceGroup?>"></span>
        </li>
        <li>
            <strong data-text="Резка, руб"></strong>
            <span data-text="<?=CurrencyFormat($arItem['PROPERTIES']['PRICE_CUTTING']['VALUE'], $arItem['ITEM_PRICES'][0]['CURRENCY']);?>"></span>
        </li>
    </ul>

    <a href="javascript:void(0)" class="main-btn product-item_buy-btn" data-text="Купить"></a>
</div>
