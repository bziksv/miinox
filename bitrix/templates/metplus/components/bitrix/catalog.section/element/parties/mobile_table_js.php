<?
/**
 * @global CMain $APPLICATION
 * @var array $arItem
 * @var string $priceGroup
 * @var array $arResult
 * @var callable $formatPropValue
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
            <strong data-text="Цена розница <?=$arResult['TABLE_UNIT']['PRICE'] ?? 'руб./шт'?>"></strong>
            <span data-text="<?=htmlspecialcharsbx((string)$priceRetailDisplay)?>"></span>
        </li>
        <li>
            <strong data-text="Цена опт <?=$arResult['TABLE_UNIT']['PRICE'] ?? 'руб./шт'?>"></strong>
            <span data-text="<?=htmlspecialcharsbx((string)$priceOptDisplay)?>"></span>
        </li>
    </ul>

    <a href="javascript:void(0)" class="main-btn product-item_buy-btn" data-text="Купить"></a>
</div>
