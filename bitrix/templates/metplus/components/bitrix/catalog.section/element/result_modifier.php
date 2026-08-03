<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

/** Свойства товаров 1С, которые показываем в таблице (CODE => заголовок). */
$propertyColumnsMap = [
    'MARKA_STALI_SPLAVA_AKH' => 'Марка',
    'DIAMETR_AKH' => 'Диаметр',
    'TOLSHCHINA_AKH' => 'Толщина',
    'DLINA_AKH' => 'Длина',
    'SHIRINA_AKH' => 'Ширина',
    'DN_AKH' => 'DN',
    'FORMA_AKH' => 'Форма',
    'POVERKHNOST_AKH' => 'Поверхность',
    'SPOSOB_PRISOEDINENIYA_AKH' => 'Соед.',
];

/** Числовые/размерные колонки — серые боксы как на metplus list_g_k */
$fieldBoxCodes = [
    'DIAMETR_AKH' => true,
    'TOLSHCHINA_AKH' => true,
    'DLINA_AKH' => true,
    'SHIRINA_AKH' => true,
    'DN_AKH' => true,
];

$propHasValue = static function ($value): bool {
    if (is_array($value)) {
        $value = implode(', ', array_filter($value, static function ($v) {
            return $v !== '' && $v !== null;
        }));
    }
    $value = trim((string)$value);
    return $value !== '' && $value !== '0' && $value !== '0.0' && $value !== '0,0';
};

$usedCodes = [];
foreach ($arResult['ITEMS'] as $arItem) {
    foreach ($propertyColumnsMap as $code => $title) {
        if (isset($usedCodes[$code])) {
            continue;
        }
        $raw = $arItem['PROPERTIES'][$code]['VALUE'] ?? '';
        if ($propHasValue($raw)) {
            $usedCodes[$code] = true;
        }
    }
}

$arResult['TABLE_PROP_COLUMNS'] = [];
foreach ($propertyColumnsMap as $code => $title) {
    if (!empty($usedCodes[$code])) {
        $arResult['TABLE_PROP_COLUMNS'][] = [
            'CODE' => $code,
            'TITLE' => $title,
            'FIELD_BOX' => !empty($fieldBoxCodes[$code]),
        ];
    }
}

// Как на test.metplus-vrn.ru/catalog/list_g_k/: цена → Шт → корзина (без дубля «Итог»)
$arResult['FIELDS'] = ['Наименование'];
foreach ($arResult['TABLE_PROP_COLUMNS'] as $col) {
    $arResult['FIELDS'][] = $col['TITLE'];
}
$arResult['FIELDS'][] = '₽ / кг<span class="product-table_th-unit">с&nbsp;НДС</span>';
$arResult['FIELDS'][] = 'Шт<span class="product-table_th-unit">кол-во</span>';
$arResult['FIELDS'][] = 'Купить';

if (!empty($arResult['PATH'])) {
    foreach (array_reverse($arResult['PATH']) as $section) {
        $curSec = CIBlockSection::GetList(
            [],
            ['IBLOCK_ID' => $section['IBLOCK_ID'], 'ID' => $section['ID']],
            false,
            ['NAME', 'UF_FIELD_TABLE']
        )->GetNext();
        if ($curSec && !empty($curSec['UF_FIELD_TABLE']) && is_array($curSec['UF_FIELD_TABLE'])) {
            foreach ($arResult['FIELDS'] as $key => $field) {
                if (isset($curSec['UF_FIELD_TABLE'][$key]) && strlen(trim((string)$curSec['UF_FIELD_TABLE'][$key]))) {
                    $arResult['FIELDS'][$key] = $curSec['UF_FIELD_TABLE'][$key];
                }
            }
            break;
        }
    }
}
