<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

require_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/catalog_icons.php";

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

$promoItems = array(
	array(
		'name' => 'Инженерная сантехника',
		'code' => 'santekhnika',
		'url' => 'https://polimer-vrn.ru/',
	),
	array(
		'name' => 'Кровельные материалы',
		'code' => 'krovlya',
		'url' => 'http://metprof-vrn.ru/',
	),
	array(
		'name' => 'Художественная ковка',
		'code' => 'kovka',
		'url' => 'https://vrn-ehk.ru/',
	),
);
?><div class="category-section">
    <div class="container">
        <ul class="category-list category-list_photo">
            <? foreach ($arResult['SECTIONS'] as &$arSection):
                $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
                $pictureSrc = '';
                if (!empty($arSection['PICTURE']['SRC'])) {
                    $pictureSrc = $arSection['PICTURE']['SRC'];
                } else {
                    $pictureSrc = miinoxResolveSubcategoryImageSrc(
                        (string)($arSection['CODE'] ?? ''),
                        (string)($arSection['NAME'] ?? ''),
                        (string)($arSection['SECTION_PAGE_URL'] ?? '')
                    );
                }
                ?>
                <li class="category-item category-item_photo" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
                    <? if($arSection['UF_IN_STOCK']): ?>
                        <div class="badge main">
                            <span class="in-stock" data-text="В наличии"></span>
                        </div>
                    <? endif; ?>
                    <a href="<?=$arSection['SECTION_PAGE_URL']?>">
                        <span class="category-item_img">
                            <? if ($pictureSrc): ?>
                                <img data-src="<?=htmlspecialcharsbx($pictureSrc)?>?v=norm2" alt="<?=htmlspecialcharsbx($arSection['NAME'])?>">
                            <? endif; ?>
                        </span>
                        <span class="category-item_text"><?=$arSection['NAME']?></span>
                    </a>
                </li>
            <?endforeach;?>
        </ul>
        <ul class="category-list category-list_mod category-list_mod-photo">
            <? foreach ($promoItems as $promo):
                $promoSrc = miinoxResolveSubcategoryImageSrc($promo['code'], $promo['name'], '');
                ?>
            <li class="category-item_mod category-item_mod-photo">
                <div class="category-item_mod-content">
                    <span class="category-item_mod-img">
                        <? if ($promoSrc): ?>
                            <img data-src="<?=htmlspecialcharsbx($promoSrc)?>" alt="<?=htmlspecialcharsbx($promo['name'])?>">
                        <? endif; ?>
                    </span>
                    <span class="category-item_mod-body">
                        <span class="text"><?=htmlspecialcharsbx($promo['name'])?></span>
                        <a href="<?=htmlspecialcharsbx($promo['url'])?>" class="site-link" target="_blank" rel="noopener">Перейти на сайт</a>
                    </span>
                </div>
            </li>
            <? endforeach; ?>
        </ul>
		<a href="/prays/price_metall.xls" class="download-price_btn main-btn"><span class="glipf-download"></span>Скачать прайс</a>
    </div>
</div>
