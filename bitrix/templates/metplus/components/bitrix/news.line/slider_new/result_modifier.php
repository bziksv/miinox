<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

foreach ($arResult['ITEMS'] as &$arItem) {
    $dbProps = CIBlockElement::GetProperty($arItem['IBLOCK_ID'], $arItem['ID'], [], []);

    while ($prop = $dbProps->GetNext()) {
        $arItem['PROPERTIES'][$prop['CODE']] = $prop;
    }

    foreach (['IMG_DESKTOP', 'IMG_TABLET', 'IMG_MOBILE'] as $code) {
        $fileId = (int)($arItem['PROPERTIES'][$code]['VALUE'] ?? 0);
        $src = $fileId > 0 ? (string)CFile::GetPath($fileId) : '';
        $arItem['SLIDER_IMAGES_ORIG'][$code] = $src;
        $arItem['SLIDER_IMAGES'][$code] = $src !== '' ? getImageWebpSrc($src) : '';
    }

    // Миграция со старого слайдера: PREVIEW_PICTURE → desktop
    if (empty($arItem['SLIDER_IMAGES']['IMG_DESKTOP'])) {
        $previewId = (int)($arItem['PREVIEW_PICTURE'] ?? 0);
        if ($previewId <= 0 && !empty($arItem['FIELDS']['PREVIEW_PICTURE'])) {
            $previewId = (int)$arItem['FIELDS']['PREVIEW_PICTURE'];
        }
        // news.line кладёт картинку как массив после обработки
        if ($previewId <= 0 && !empty($arItem['PREVIEW_PICTURE']['ID'])) {
            $previewId = (int)$arItem['PREVIEW_PICTURE']['ID'];
        }
        if ($previewId > 0) {
            $src = (string)CFile::GetPath($previewId);
            $arItem['SLIDER_IMAGES_ORIG']['IMG_DESKTOP'] = $src;
            $arItem['SLIDER_IMAGES']['IMG_DESKTOP'] = $src !== '' ? getImageWebpSrc($src) : '';
        } elseif (!empty($arItem['PREVIEW_PICTURE']['SRC'])) {
            $src = (string)$arItem['PREVIEW_PICTURE']['SRC'];
            $arItem['SLIDER_IMAGES_ORIG']['IMG_DESKTOP'] = $src;
            $arItem['SLIDER_IMAGES']['IMG_DESKTOP'] = getImageWebpSrc($src);
        }
    }

    if (empty($arItem['SLIDER_IMAGES']['IMG_TABLET'])) {
        $arItem['SLIDER_IMAGES']['IMG_TABLET'] = $arItem['SLIDER_IMAGES']['IMG_DESKTOP'];
        $arItem['SLIDER_IMAGES_ORIG']['IMG_TABLET'] = $arItem['SLIDER_IMAGES_ORIG']['IMG_DESKTOP'] ?? '';
    }

    if (empty($arItem['SLIDER_IMAGES']['IMG_MOBILE'])) {
        $arItem['SLIDER_IMAGES']['IMG_MOBILE'] = $arItem['SLIDER_IMAGES']['IMG_TABLET']
            ?: $arItem['SLIDER_IMAGES']['IMG_DESKTOP'];
        $arItem['SLIDER_IMAGES_ORIG']['IMG_MOBILE'] = $arItem['SLIDER_IMAGES_ORIG']['IMG_TABLET']
            ?: ($arItem['SLIDER_IMAGES_ORIG']['IMG_DESKTOP'] ?? '');
    }

    $arItem['SLIDER_LINK'] = trim((string)($arItem['PROPERTIES']['LINK']['VALUE'] ?? ''));
}
unset($arItem);
