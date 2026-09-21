<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

/**
 * Единица продажи раздела: метры или штуки (кг — тот же набор колонок).
 * Заголовок таблицы один на раздел, по самой частой единице товаров.
 */
$unitPresets = [
    'm' => [
        'CODE' => 'm',
        'PRICE' => 'руб./м',
        'QTY' => 'Длина, м',
        'STEP' => '0.01',
        'MIN' => '0.01',
    ],
    'pc' => [
        'CODE' => 'pc',
        'PRICE' => 'руб./шт',
        'QTY' => 'Кол-во, шт',
        'STEP' => '1',
        'MIN' => '1',
    ],
    'kg' => [
        'CODE' => 'kg',
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

$measures = [];
if ($itemIds) {
    $productRes = CCatalogProduct::GetList(
        [],
        ['ID' => $itemIds],
        false,
        false,
        ['ID', 'MEASURE']
    );
    while ($product = $productRes->Fetch()) {
        $measures[(int)$product['ID']] = (int)$product['MEASURE'];
    }
}

$unitVotes = [];
foreach ($itemIds as $itemId) {
    $unitCode = $measureToUnit[$measures[$itemId] ?? 0] ?? 'pc';
    $unitVotes[$unitCode] = ($unitVotes[$unitCode] ?? 0) + 1;
}
arsort($unitVotes);
$unitCode = $unitVotes ? (string)array_key_first($unitVotes) : 'pc';
$unit = $unitPresets[$unitCode];

$arResult['TABLE_UNIT'] = $unit;
$arResult['FIELDS'] = [
    'Наименование',
    'Цена розница<span class="product-table_th-unit">' . $unit['PRICE'] . '</span>',
    'Цена опт<span class="product-table_th-unit">' . $unit['PRICE'] . '</span>',
    $unit['QTY'],
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
