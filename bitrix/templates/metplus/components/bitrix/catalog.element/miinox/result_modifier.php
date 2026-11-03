<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$productId = (int)$arResult['ID'];
$iblockId = (int)$arResult['IBLOCK_ID'];

$measureToUnit = [
    1 => ['CODE' => 'm', 'SHORT' => 'м', 'LABEL' => 'метр'],
    4 => ['CODE' => 'kg', 'SHORT' => 'кг', 'LABEL' => 'килограмм'],
    5 => ['CODE' => 'pc', 'SHORT' => 'шт', 'LABEL' => 'штука'],
];
$measureId = 5;
$product = [];
if (CModule::IncludeModule('catalog')) {
    $product = CCatalogProduct::GetByID($productId) ?: [];
    if ($product) {
        $measureId = (int)$product['MEASURE'];
    }
}
$arResult['CARD_UNIT'] = $measureToUnit[$measureId] ?? $measureToUnit[5];

$priceOf = static function (int $productId, int $groupId): ?float {
    if (!CModule::IncludeModule('catalog')) {
        return null;
    }
    $res = CPrice::GetList([], ['PRODUCT_ID' => $productId, 'CATALOG_GROUP_ID' => $groupId]);
    if ($row = $res->Fetch()) {
        $value = (float)$row['PRICE'];
        return $value > 0 ? $value : null;
    }
    return null;
};
$arResult['CARD_PRICE_RETAIL'] = $priceOf($productId, 16);
$arResult['CARD_PRICE_OPT'] = $priceOf($productId, 17);

$images = [];
foreach (['DETAIL_PICTURE', 'PREVIEW_PICTURE'] as $field) {
    $picture = $arResult[$field] ?? null;
    if (is_array($picture) && !empty($picture['SRC'])) {
        $images[] = $picture['SRC'];
    } elseif (is_numeric($picture) && (int)$picture > 0) {
        $src = CFile::GetPath((int)$picture);
        if ($src) {
            $images[] = $src;
        }
    }
}
$arResult['CARD_IMAGES'] = array_values(array_unique($images));
$arResult['CARD_PLACEHOLDER'] = $arResult['CARD_IMAGES'] === [];
if ($arResult['CARD_PLACEHOLDER']) {
    $base = SITE_TEMPLATE_PATH . '/img/static/';
    $arResult['CARD_IMAGES'] = [
        $base . 'product-ph-1.svg',
        $base . 'product-ph-2.svg',
        $base . 'product-ph-3.svg',
        $base . 'product-ph-4.svg',
    ];
}

$sectionId = (int)($arResult['IBLOCK_SECTION_ID'] ?? 0);
$sectionCode = '';
$crumbs = [
    ['TITLE' => 'Главная страница', 'LINK' => '/'],
    ['TITLE' => 'Каталог', 'LINK' => '/catalog/'],
];
if ($sectionId > 0) {
    $nav = CIBlockSection::GetNavChain($iblockId, $sectionId, ['ID', 'NAME', 'SECTION_PAGE_URL', 'CODE']);
    while ($section = $nav->GetNext()) {
        $crumbs[] = [
            'TITLE' => $section['NAME'],
            'LINK' => $section['SECTION_PAGE_URL'],
        ];
        $sectionCode = (string)$section['CODE'];
    }
}
$crumbs[] = ['TITLE' => (string)$arResult['NAME'], 'LINK' => ''];
$arResult['CARD_CRUMBS'] = $crumbs;

$qty = isset($product['QUANTITY']) ? (float)$product['QUANTITY'] : 0.0;
$thresholds = include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/stock_thresholds.php';
$stockLimit = $thresholds[$sectionCode ?? ''] ?? 1000;
$arResult['CARD_STOCK_LIMITED'] = $qty <= (float)$stockLimit;

$skipCodes = [
    'SEO_NAME',
    'MORE_PHOTO',
    'vote_count',
    'vote_sum',
    'rating',
    'BLOG_POST_ID',
    'BLOG_COMMENTS_CNT',
];
$specs = [];
$res = CIBlockElement::GetProperty($iblockId, $productId, ['sort' => 'asc', 'id' => 'asc'], ['ACTIVE' => 'Y']);
while ($prop = $res->Fetch()) {
    $code = (string)$prop['CODE'];
    if ($code === '' || in_array($code, $skipCodes, true) || strncmp($code, 'CML2_', 5) === 0) {
        continue;
    }
    $value = trim((string)($prop['VALUE_ENUM'] ?: $prop['VALUE']));
    if ($value === '' || $value === '0') {
        continue;
    }
    $name = trim((string)preg_replace('/\s*\(АХ\)\s*$/u', '', (string)$prop['NAME']));
    if (preg_match('/исключ|витрин|sscc|обрезать|служебн|публикац/ui', $name . ' ' . $code)) {
        continue;
    }
    if (!isset($specs[$code])) {
        $specs[$code] = ['NAME' => $name !== '' ? $name : $code, 'VALUES' => []];
    }
    if (!in_array($value, $specs[$code]['VALUES'], true)) {
        $specs[$code]['VALUES'][] = $value;
    }
}
$arResult['CARD_SPECS'] = array_values($specs);
