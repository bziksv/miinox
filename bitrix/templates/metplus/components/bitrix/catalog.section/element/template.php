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

$unit = $arResult['TABLE_UNIT'] ?? [
    'PRICE' => 'руб./шт',
    'QTY' => 'Кол-во, шт',
    'STEP' => '1',
    'MIN' => '1',
];
$retailColIndex = 1;
$optColIndex = 2;
$qtyColIndex = 3;
$buyColIndex = 4;
?>

<? $tableFilters = $arResult['TABLE_FILTERS'] ?? []; ?>
<div class="product-table-smart-search" data-product-table-search>
    <label class="product-table-smart-search__label" for="product-table-smart-search-input">Поиск по таблице</label>
    <div class="product-table-toolbar__row">
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
        <? if ($tableFilters): ?>
        <button
            type="button"
            class="product-table-filter-btn"
            aria-expanded="false"
            aria-controls="product-table-filters"
        >
            <svg class="product-table-filter-btn__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M4 6h16M7 12h10M10 18h4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <span>Фильтры</span>
            <span class="product-table-filter-btn__count" hidden>0</span>
            <svg class="product-table-filter-btn__chevron" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <? endif; ?>
    </div>
    <? if ($tableFilters): ?>
    <div class="product-table-filters" id="product-table-filters">
        <div class="product-table-filters__clip">
            <div class="product-table-filters__panel">
                <div class="product-table-filters__grid">
                    <? foreach ($tableFilters as $filter): ?>
                    <div class="product-table-filters__item product-table-ms" data-filter-code="<?=htmlspecialcharsbx($filter['CODE'])?>">
                        <span class="product-table-filters__name"><?=htmlspecialcharsbx($filter['TITLE'])?></span>
                        <button type="button" class="product-table-ms__toggle" aria-expanded="false">
                            <span class="product-table-ms__value">Все</span>
                            <svg class="product-table-ms__chevron" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div class="product-table-ms__menu" hidden>
                            <? foreach ($filter['VALUES'] as $filterValue): ?>
                            <label class="product-table-ms__option">
                                <input type="checkbox" value="<?=htmlspecialcharsbx($filterValue)?>">
                                <span><?=htmlspecialcharsbx($filterValue)?></span>
                            </label>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <? endforeach; ?>
                </div>
                <div class="product-table-filters__foot">
                    <button type="button" class="product-table-filters__reset" hidden>Сбросить фильтры</button>
                </div>
            </div>
        </div>
    </div>
    <? endif; ?>
    <div class="product-table-smart-search__meta" aria-live="polite"></div>
</div>

<div class="product-table-wrap">
<table class="product-table" id="product-table">
    <thead>
        <tr>
            <? foreach ($arResult['FIELDS'] as $key => $field):
                $thClass = [];
                if ($key === $buyColIndex) {
                    $thClass[] = 'product-table_col-buy';
                } elseif ($key === $qtyColIndex) {
                    $thClass[] = 'product-table_col-qty';
                } elseif ($key === $retailColIndex || $key === $optColIndex) {
                    $thClass[] = 'product-table_col-price';
                }
                ?>
            <th data-index="<?=$key?>"<?=$thClass ? ' class="'.implode(' ', $thClass).'"' : ''?>>
                <?=$field?>
            </th>
            <? endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?
        $inc_manager = 0;
        $inc_instock = 0;
        foreach ($arResult['ITEMS'] as $i => $arItem):
            $priceRetail = getGroupPriceForProduct(16, $arItem['ID']) ?: '';
            $priceOpt = getGroupPriceForProduct(17, $arItem['ID']) ?: '';
            $priceRetailDisplay = $priceRetail !== '' ? $priceRetail : 'по запросу';
            $priceOptDisplay = $priceOpt !== '' ? $priceOpt : 'по запросу';
            $limited = (int)$arItem['CATALOG_QUANTITY'] < 1000;
            $availTip = $limited
                ? 'Количество ограничено, уточняйте у менеджера.'
                : 'В наличии на складе.';
            $productName = ($arItem['PROPERTIES']['SEO_NAME']['VALUE'])
                ? $arItem['PROPERTIES']['SEO_NAME']['VALUE']
                : htmlspecialchars_decode(preg_replace(['|[\s]+|s', '/\(|\)/'], [' ', '"'], trim($arItem['NAME'])));
            $rowProps = $arResult['TABLE_ROW_PROPS'][(int)$arItem['ID']] ?? [];
            $rowPropsJson = $rowProps
                ? htmlspecialcharsbx(json_encode($rowProps, JSON_UNESCAPED_UNICODE))
                : '';
            ?>
        <tr<?=$rowPropsJson !== '' ? ' data-props="'.$rowPropsJson.'"' : ''?>>
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

            <td class="product-table_cell-price<?=$priceRetail === '' ? ' product-table_cell-price--empty' : ''?>"><?=$priceRetailDisplay?></td>
            <td class="product-table_cell-price<?=$priceOpt === '' ? ' product-table_cell-price--empty' : ''?>"><?=$priceOptDisplay?></td>

            <td class="product-table_cell-qty">
                <div class="product-table_field">
                    <input type="number" class="product-table-input" name="pieces" min="<?=$unit['MIN']?>" step="<?=$unit['STEP']?>" value="1" placeholder="0" inputmode="decimal">
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
