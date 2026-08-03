<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

$this->setFrameMode(true);

if (!count($arResult['ITEMS'])) {
    return;
}

$formatPropValue = static function ($value): string {
    if (is_array($value)) {
        $value = implode(', ', array_filter($value, static function ($v) {
            return $v !== '' && $v !== null;
        }));
    }
    $value = trim((string)$value);
    if ($value === '' || $value === '0' || $value === '0.0' || $value === '0,0') {
        return '';
    }
    return htmlspecialcharsbx($value);
};

$propColumns = $arResult['TABLE_PROP_COLUMNS'] ?? [];
$priceColIndex = 1 + count($propColumns);
$qtyColIndex = $priceColIndex + 1;
$buyColIndex = $qtyColIndex + 1;
$fieldCount = count($arResult['FIELDS'] ?? []);
?>

<? if ($arResult['UF_HIDDEN_COL']): ?>
    <?
    $hidden_cols = explode(',', $arResult['UF_HIDDEN_COL']);
    foreach ($hidden_cols as $col): ?>
    <style>
        .product-table tr th:nth-child(<?=(int)$col?>),
        .product-table tr td:nth-child(<?=(int)$col?>){
            display: none;
        }
    </style>
    <? endforeach; ?>
<? endif; ?>

<?
$COLUMNS_ATTR = [];
if ($arResult['UF_COLUMNS_ATTR']) {
    $rsGender = CUserFieldEnum::GetList([], ["USER_FIELD_NAME" => "UF_COLUMNS_ATTR", "ID" => $arResult['UF_COLUMNS_ATTR']]);
    while ($arEnum = $rsGender->Fetch()) {
        $COLUMNS_ATTR[] = $arEnum["XML_ID"];
    }
}
?>

<div class="product-table-smart-search" data-product-table-search>
    <label class="product-table-smart-search__label" for="product-table-smart-search-input">Поиск по таблице</label>
    <div class="product-table-smart-search__field">
        <span class="product-table-smart-search__icon" aria-hidden="true"></span>
        <input
            id="product-table-smart-search-input"
            class="product-table-smart-search__input"
            type="search"
            placeholder="Название, марка стали, размер…"
            autocomplete="off"
            enterkeyhint="search"
        >
        <button type="button" class="product-table-smart-search__clear" hidden aria-label="Очистить поиск">&times;</button>
    </div>
    <div class="product-table-smart-search__meta" aria-live="polite"></div>
</div>

<div class="product-table-wrap">
<table class="product-table" id="product-table">
    <thead>
        <tr>
            <? foreach ($arResult['FIELDS'] as $key => $field):
                $thClass = [];
                if ($key === $buyColIndex || ($fieldCount && $key === $fieldCount - 1)) {
                    $thClass[] = 'product-table_col-buy';
                } elseif ($key === $qtyColIndex) {
                    $thClass[] = 'product-table_col-qty';
                } elseif ($key === $priceColIndex) {
                    $thClass[] = 'product-table_col-price';
                } elseif ($key === 1) {
                    $thClass[] = 'product-table_col-steel';
                } elseif ($key > 0 && $key < $priceColIndex) {
                    $thClass[] = 'product-table_col-qty';
                }
                ?>
            <th data-index="<?=$key?>"<?=$thClass ? ' class="'.implode(' ', $thClass).'"' : ''?>>
                <? if ($key === $buyColIndex || ($fieldCount && $key === $fieldCount - 1)): ?>
                    <span class="sr-only">Купить</span>
                <? else: ?>
                    <?=$field?>
                <? endif; ?>
            </th>
            <? endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?
        $inc_manager = 0;
        $inc_instock = 0;
        foreach ($arResult['ITEMS'] as $i => $arItem):
            $priceGroup = getGroupPriceForProduct(16, $arItem['ID']);
            $price = array_map(static function ($val) {
                return $val['PRINT_PRICE'] ?? $val['PRINT_DISCOUNT'] ?? '';
            }, $arItem['ITEM_PRICES'] ?: []);
            $price = array_values(array_filter($price));
            if (!$priceGroup && !empty($price)) {
                $priceGroup = $price[0];
            }
            if (!$priceGroup && !empty($arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'])) {
                $priceGroup = $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'];
            }
            if (!$price && $priceGroup) {
                $price = [$priceGroup];
            }
            $priceDisplay = $priceGroup ?: 'по запросу';
            $limited = (int)$arItem['CATALOG_QUANTITY'] < 1000;
            $availTip = $limited
                ? 'Количество ограничено, уточняйте у менеджера.'
                : 'В наличии на складе.';
            $productName = ($arItem['PROPERTIES']['SEO_NAME']['VALUE'])
                ? $arItem['PROPERTIES']['SEO_NAME']['VALUE']
                : htmlspecialchars_decode(preg_replace(['|[\s]+|s', '/\(|\)/'], [' ', '"'], trim($arItem['NAME'])));
            ?>
        <tr>
            <td class="product-table_first-cell">
                <button type="button" class="product-availability-marker<?=$limited ? ' product-availability-marker--limited' : ''?>" aria-label="<?=htmlspecialcharsbx($availTip)?>">
                    <span class="product-availability-marker__tip"><?=htmlspecialcharsbx($availTip)?></span>
                </button>
                <span class="product-item_name<?=$limited ? ' product-item_name-mod' : ''?>">
					<a href="javascript:void(0)"><?=$productName?></a>
                </span>
                <span class="product-availability">
                    <? if ($limited): ?>
                        <? if ($arResult['UF_JS_MANAGER'] && $arResult['UF_JS_MANAGER'] <= $inc_manager): ?>
                            <span class="manager" data-text="Количество ограничено, уточняйте у менеджера."></span>
                        <? else: ?>
                            <span class="manager">Количество ограничено, уточняйте у менеджера.</span>
                        <? endif; ?>
                        <? $inc_manager++; ?>
                    <? else: ?>
                        <? if ($arResult['UF_JS_INSTOCK'] && $arResult['UF_JS_INSTOCK'] <= $inc_instock): ?>
                            <span class="instock" data-text="В наличии на складе."></span>
                        <? else: ?>
                            <span class="instock">В наличии на складе.</span>
                        <? endif; ?>
                        <? $inc_instock++; ?>
                    <? endif; ?>
                </span>

                <?
                if ($arResult['UF_JS_MOBILE_TABLE'] <= $i) {
                    include __DIR__ . "/parties/mobile_table_js.php";
                } else {
                    include __DIR__ . "/parties/mobile_table.php";
                }
                ?>
            </td>

            <? foreach ($propColumns as $colIndex => $col):
                $cellValue = $formatPropValue($arItem['PROPERTIES'][$col['CODE']]['VALUE'] ?? '');
                $attrIndex = $colIndex + 1; // 0 = name
                $useFieldBox = !empty($col['FIELD_BOX']) && $cellValue !== '';
                ?>
                <? if (in_array((string)$attrIndex, $COLUMNS_ATTR, true) || in_array($attrIndex, $COLUMNS_ATTR, true)): ?>
                    <td class="product-table_cell-qty" data-text="<?=$cellValue?>"></td>
                <? elseif ($useFieldBox): ?>
                    <td class="product-table_cell-qty">
                        <div class="product-table_field product-table_field--restricted">
                            <span class="product-table_field-value"><?=$cellValue?></span>
                        </div>
                    </td>
                <? else: ?>
                    <td class="<?=($colIndex === 0) ? 'product-table_cell-steel' : ''?>"><?=$cellValue?></td>
                <? endif; ?>
            <? endforeach; ?>

            <? if (in_array((string)$priceColIndex, $COLUMNS_ATTR, true) || in_array($priceColIndex, $COLUMNS_ATTR, true)): ?>
                <td data-text="<?=htmlspecialcharsbx((string)$priceDisplay)?>"></td>
            <? else: ?>
                <td class="product-table_cell-price<?=!$priceGroup ? ' product-table_cell-price--empty' : ''?>"><?=$priceDisplay?></td>
            <? endif; ?>

            <td class="product-table_cell-qty">
                <div class="product-table_field">
                    <input type="number" class="product-table-input" name="pieces" min="1" step="1" value="1" placeholder="0" inputmode="numeric">
                </div>
            </td>

            <td class="product-table_col-buy">
                <a href="javascript:void(0)" class="add-to-cart-action product-item_cart-btn" id="<?=$arItem['ID']?>" title="В корзину" aria-label="В корзину"><span class="glipf-cart"></span></a>
            </td>
        </tr>
        <? endforeach; ?>
    </tbody>
</table>
</div>

<div class="row product-table-legend-row">
    <div class="col-md-6">
        <div class="product-availability_text">Наличие товара на складе</div>
        <div class="product-availability_text yellow">Количество ограничено, уточняйте у менеджера</div>
    </div>

    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
    <div class="col-md-6">
        <?=$arResult["NAV_STRING"]?>
    </div>
    <? endif; ?>
</div>

<? if ($arParams["DEPTH_LEVEL"] == "1"): ?>
	<div class="unified-text-section"><?=$arResult['DESCRIPTION'];?></div>
<? endif; ?>
