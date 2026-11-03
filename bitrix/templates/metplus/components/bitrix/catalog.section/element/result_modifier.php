<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

/**
 * Единица продажи раздела — по товарам с розничной ценой во всём разделе,
 * не по текущей странице и не по позициям «по запросу».
 * У строки остаётся её собственная единица: отрезок в штуках не становится метрами.
 */
$unitPresets = [
    'm' => [
        'CODE' => 'm',
        'SHORT' => 'м',
        'PRICE' => 'руб./м',
        'QTY' => 'Длина, м',
        'STEP' => '0.01',
        'MIN' => '0.01',
    ],
    'pc' => [
        'CODE' => 'pc',
        'SHORT' => 'шт',
        'PRICE' => 'руб./шт',
        'QTY' => 'Кол-во, шт',
        'STEP' => '1',
        'MIN' => '1',
    ],
    'kg' => [
        'CODE' => 'kg',
        'SHORT' => 'кг',
        'PRICE' => 'руб./кг',
        'QTY' => 'Кол-во, кг',
        'STEP' => '0.01',
        'MIN' => '0.01',
    ],
];

$measureToUnit = [
    1 => 'm',
    4 => 'kg',
    5 => 'pc',
];

$itemIds = [];
foreach ($arResult['ITEMS'] as $arItem) {
    $itemIds[] = (int)$arItem['ID'];
}

$sectionId = (int)($arResult['ID'] ?? 0);
$sectionIds = [];
if ($sectionId > 0 && CModule::IncludeModule('iblock')) {
    $elRes = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => (int)$arParams['IBLOCK_ID'],
            'SECTION_ID' => $sectionId,
            'INCLUDE_SUBSECTIONS' => 'N',
            'ACTIVE' => 'Y',
        ],
        false,
        false,
        ['ID']
    );
    while ($el = $elRes->Fetch()) {
        $sectionIds[] = (int)$el['ID'];
    }
}
if (!$sectionIds) {
    $sectionIds = $itemIds;
}

$pricedIds = [];
if ($sectionIds && CModule::IncludeModule('catalog')) {
    $priceRes = CPrice::GetList(
        [],
        [
            'PRODUCT_ID' => $sectionIds,
            'CATALOG_GROUP_ID' => 16,
            '>PRICE' => 0,
        ],
        false,
        false,
        ['PRODUCT_ID']
    );
    while ($price = $priceRes->Fetch()) {
        $pricedIds[(int)$price['PRODUCT_ID']] = true;
    }
}
$voteIds = $pricedIds ? array_keys($pricedIds) : $sectionIds;
$measureIds = array_values(array_unique(array_merge($voteIds, $itemIds)));

$measures = [];
if ($measureIds) {
    $productRes = CCatalogProduct::GetList(
        [],
        ['ID' => $measureIds],
        false,
        false,
        ['ID', 'MEASURE']
    );
    while ($product = $productRes->Fetch()) {
        $measures[(int)$product['ID']] = (int)$product['MEASURE'];
    }
}

$unitOf = static function (int $measureId) use ($measureToUnit): string {
    return $measureToUnit[$measureId] ?? 'pc';
};

$unitVotes = [];
foreach ($voteIds as $voteId) {
    $code = $unitOf((int)($measures[$voteId] ?? 0));
    $unitVotes[$code] = ($unitVotes[$code] ?? 0) + 1;
}
arsort($unitVotes);
$unitCode = $unitVotes ? (string)array_key_first($unitVotes) : 'pc';
$unit = $unitPresets[$unitCode];

$arResult['TABLE_ROW_UNITS'] = [];
foreach ($itemIds as $itemId) {
    $code = $unitOf((int)($measures[$itemId] ?? 0));
    $arResult['TABLE_ROW_UNITS'][$itemId] = $unitPresets[$code];
}

$arResult['TABLE_UNIT'] = $unit;

$stockThresholds = include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/stock_thresholds.php';
$sectionCode = (string)($arResult['CODE'] ?? '');
$arResult['STOCK_THRESHOLD'] = $stockThresholds[$sectionCode] ?? 1000;
$arResult['FIELDS'] = [
    'Наименование',
    'Цена розница<span class="product-table_th-unit">' . $unit['PRICE'] . '</span>',
    'Цена опт<span class="product-table_th-unit">' . $unit['PRICE'] . '</span>',
    'Кол-во',
    'В корзину',
];

/** Свойства для раскрывающегося фильтра над таблицей. */
$filterProps = [
    'TOLSHCHINA_AKH' => 'Толщина',
    'DLINA_AKH' => 'Длина',
    'SHIRINA_AKH' => 'Ширина',
    'SPOSOB_PRISOEDINENIYA_AKH' => 'Соединение',
    'DN_AKH' => 'DN',
    'POVERKHNOST_AKH' => 'Поверхность',
    'MARKA_STALI_SPLAVA_AKH' => 'Марка',
];

$filterValue = static function ($raw): array {
    $parts = is_array($raw) ? $raw : [$raw];
    $out = [];
    foreach ($parts as $part) {
        $part = trim((string)$part);
        if ($part === '' || $part === '0' || $part === '0.0' || $part === '0,0') {
            continue;
        }
        $out[] = $part;
    }
    return array_values(array_unique($out));
};

$facetValues = [];
$arResult['TABLE_ROW_PROPS'] = [];
foreach ($arResult['ITEMS'] as $arItem) {
    $rowProps = [];
    foreach ($filterProps as $code => $title) {
        $values = $filterValue($arItem['PROPERTIES'][$code]['VALUE'] ?? '');
        if (!$values) {
            continue;
        }
        $rowProps[$code] = $values;
        foreach ($values as $value) {
            $facetValues[$code][$value] = true;
        }
    }
    $arResult['TABLE_ROW_PROPS'][(int)$arItem['ID']] = $rowProps;
}

$sortFilterValues = static function (array $values): array {
    usort($values, static function ($a, $b) {
        $na = str_replace(',', '.', $a);
        $nb = str_replace(',', '.', $b);
        $aNum = is_numeric($na);
        $bNum = is_numeric($nb);
        if ($aNum && $bNum) {
            return (float)$na <=> (float)$nb;
        }
        return strnatcasecmp($a, $b);
    });
    return $values;
};

$arResult['TABLE_FILTERS'] = [];
foreach ($filterProps as $code => $title) {
    if (empty($facetValues[$code]) || count($facetValues[$code]) < 2) {
        continue;
    }
    $arResult['TABLE_FILTERS'][] = [
        'CODE' => $code,
        'TITLE' => $title,
        'VALUES' => $sortFilterValues(array_keys($facetValues[$code])),
    ];
}
