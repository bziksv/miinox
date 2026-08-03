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
            <strong>Наименование товара</strong>
            <span class="product-item_name"><?=($arItem['PROPERTIES']['SEO_NAME']['VALUE']) ? $arItem['PROPERTIES']['SEO_NAME']['VALUE'] : htmlspecialchars_decode(preg_replace(array('|[\s]+|s','/\(|\)/'), array(' ', '"'), trim($arItem['NAME'])))?></span>
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
            <strong><?=htmlspecialcharsbx($col['TITLE'])?></strong>
            <?=$val?>
        </li>
        <? endforeach; ?>
        <li>
            <strong>Цена руб/кг (с НДС)</strong>
            <?=$priceGroup?>
        </li>
    </ul>

    <a href="javascript:void(0)" class="main-btn product-item_buy-btn">Купить</a>
</div>
