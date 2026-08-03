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
    // news.line отдаёт PREVIEW_PICTURE массивом — нельзя кастить (int) в PHP 8+
    if (empty($arItem['SLIDER_IMAGES']['IMG_DESKTOP'])) {
        $previewId = 0;
        $previewSrc = '';
        $preview = $arItem['PREVIEW_PICTURE'] ?? null;
        if (is_array($preview)) {
            $previewId = (int)($preview['ID'] ?? 0);
            $previewSrc = (string)($preview['SRC'] ?? '');
        } elseif (is_numeric($preview)) {
            $previewId = (int)$preview;
        }
        if ($previewId <= 0 && !empty($arItem['FIELDS']['PREVIEW_PICTURE'])) {
            $fieldPreview = $arItem['FIELDS']['PREVIEW_PICTURE'];
            if (is_array($fieldPreview)) {
                $previewId = (int)($fieldPreview['ID'] ?? 0);
                if ($previewSrc === '') {
                    $previewSrc = (string)($fieldPreview['SRC'] ?? '');
                }
            } elseif (is_numeric($fieldPreview)) {
                $previewId = (int)$fieldPreview;
            }
        }
        if ($previewId > 0) {
            $src = (string)CFile::GetPath($previewId);
            $arItem['SLIDER_IMAGES_ORIG']['IMG_DESKTOP'] = $src;
            $arItem['SLIDER_IMAGES']['IMG_DESKTOP'] = $src !== '' ? getImageWebpSrc($src) : '';
        } elseif ($previewSrc !== '') {
            $arItem['SLIDER_IMAGES_ORIG']['IMG_DESKTOP'] = $previewSrc;
            $arItem['SLIDER_IMAGES']['IMG_DESKTOP'] = getImageWebpSrc($previewSrc);
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
