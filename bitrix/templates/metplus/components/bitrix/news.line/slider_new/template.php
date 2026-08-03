<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */
/** @global CMain $APPLICATION */

$this->setFrameMode(true);

if (empty($arResult['ITEMS'])) {
    return;
}
?>
<section class="main-section main-section--picture">
    <div class="main-slider main-slider--picture">
        <?php foreach ($arResult['ITEMS'] as $arItem):
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
            $this->AddDeleteAction(
                $arItem['ID'],
                $arItem['DELETE_LINK'],
                CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'),
                ['CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]
            );

            $desktop = $arItem['SLIDER_IMAGES']['IMG_DESKTOP'] ?? '';
            $tablet = $arItem['SLIDER_IMAGES']['IMG_TABLET'] ?? '';
            $mobile = $arItem['SLIDER_IMAGES']['IMG_MOBILE'] ?? '';
            $desktopOrig = $arItem['SLIDER_IMAGES_ORIG']['IMG_DESKTOP'] ?? $desktop;
            $tabletOrig = $arItem['SLIDER_IMAGES_ORIG']['IMG_TABLET'] ?? $tablet;
            $mobileOrig = $arItem['SLIDER_IMAGES_ORIG']['IMG_MOBILE'] ?? $mobile;
            $link = $arItem['SLIDER_LINK'] ?? '';
            $alt = htmlspecialcharsbx($arItem['NAME']);

            if ($desktop === '') {
                continue;
            }
            ?>
            <div class="main-slide main-slide--picture" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
                <?php if ($link !== ''): ?>
                <a class="main-slide__link" href="<?=htmlspecialcharsbx($link)?>">
                <?php endif; ?>

                    <picture class="main-slide__picture">
                        <?php if ($mobile !== '' && $mobile !== $mobileOrig): ?>
                        <source type="image/webp" media="(max-width: 767px)" srcset="<?=htmlspecialcharsbx($mobile)?>">
                        <?php endif; ?>
                        <source media="(max-width: 767px)" srcset="<?=htmlspecialcharsbx($mobileOrig)?>">

                        <?php if ($tablet !== '' && $tablet !== $tabletOrig): ?>
                        <source type="image/webp" media="(max-width: 1199px)" srcset="<?=htmlspecialcharsbx($tablet)?>">
                        <?php endif; ?>
                        <source media="(max-width: 1199px)" srcset="<?=htmlspecialcharsbx($tabletOrig)?>">

                        <?php if ($desktop !== '' && $desktop !== $desktopOrig): ?>
                        <source type="image/webp" srcset="<?=htmlspecialcharsbx($desktop)?>">
                        <?php endif; ?>
                        <img
                            class="main-slide__img"
                            src="<?=htmlspecialcharsbx(($desktop !== '' && $desktop !== $desktopOrig) ? $desktop : $desktopOrig)?>"
                            alt="<?=$alt?>"
                            loading="eager"
                            decoding="async"
                        >
                    </picture>

                <?php if ($link !== ''): ?>
                </a>
                <?php endif; ?>

                <?php if (($arItem['PROPERTIES']['ADS']['VALUE_XML_ID'] ?? '') === 'Y'): ?>
                <div class="ads-block ads-block--picture">
                    <?php
                    $APPLICATION->IncludeComponent('prime:ads.btn', '', [
                        'SHOW' => 'Y',
                        'DESCRIPTION' => 'ООО «Металлинвест Инокс»',
                        'POSITOPN' => 'tooltip-top-right',
                    ]);
                    ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>
