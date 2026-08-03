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
        <? foreach (($arResult['TABLE_PROP_COLUMNS'] ?? []) as $col):
            $val = is_callable($formatPropValue ?? null)
                ? $formatPropValue($arItem['PROPERTIES'][$col['CODE']]['VALUE'] ?? '')
                : htmlspecialcharsbx((string)($arItem['PROPERTIES'][$col['CODE']]['VALUE'] ?? ''));
            if ($val === '') {
                continue;
            }
            ?>
        <li>
            <strong data-text="<?=htmlspecialcharsbx($col['TITLE'])?>"></strong>
            <span data-text="<?=$val?>"></span>
        </li>
        <? endforeach; ?>
        <li>
            <strong data-text="Цена руб/кг (с НДС)"></strong>
            <span data-text="<?=htmlspecialcharsbx((string)$priceGroup)?>"></span>
        </li>
    </ul>

    <a href="javascript:void(0)" class="main-btn product-item_buy-btn" data-text="Купить"></a>
</div>
