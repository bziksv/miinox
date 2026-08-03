<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

$promoItems = array(
	array(
		'name' => 'Кровельные материалы',
		'code' => 'krovlya',
		'url' => 'http://metprof-vrn.ru/',
	),
	array(
		'name' => 'Инженерная сантехника',
		'code' => 'santekhnika',
		'url' => 'https://polimer-vrn.ru/',
	),
	array(
		'name' => 'Художественная ковка',
		'code' => 'kovka',
		'url' => 'https://vrn-ehk.ru/',
	),
);
?>
<section class="catalog-index">
	<div class="container">
		<div class="catalog-index__layout">
			<div class="catalog-index__main">
				<? $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "sections", array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
					"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
					"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
					"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
					"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
					"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
					"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
					"SECTION_USER_FIELDS" => array("UF_*")
				),
					$component,
					($arParams["SHOW_TOP_ELEMENTS"] !== "N" ? array("HIDE_ICONS" => "Y") : array())
				);?>
			</div>

			<aside class="catalog-index__side">
				<div class="catalog-index__partners">
					<h2 class="catalog-index__side-title">Смежные направления</h2>
					<ul class="catalog-index__partner-list">
						<? foreach ($promoItems as $promo):
							$promoSrc = miinoxResolveSubcategoryImageSrc($promo['code'], $promo['name'], '');
							?>
						<li>
							<a class="catalog-index__partner" href="<?=htmlspecialcharsbx($promo['url'])?>" target="_blank" rel="noopener">
								<span class="catalog-index__partner-media">
									<? if ($promoSrc): ?>
										<img src="<?=htmlspecialcharsbx($promoSrc)?>" alt="<?=htmlspecialcharsbx($promo['name'])?>" loading="lazy">
									<? endif; ?>
								</span>
								<span class="catalog-index__partner-body">
									<span class="catalog-index__partner-name"><?=htmlspecialcharsbx($promo['name'])?></span>
									<span class="catalog-index__partner-go">Перейти на сайт</span>
								</span>
							</a>
						</li>
						<? endforeach; ?>
					</ul>
				</div>

				<a class="catalog-index__price" href="/prays/price_metall.xls">
					<span class="catalog-index__price-bg" aria-hidden="true">
						<img src="<?=SITE_TEMPLATE_PATH?>/img/static/price-list_img.jpg" alt="" loading="lazy">
					</span>
					<span class="catalog-index__price-body">
						<span class="catalog-index__price-label">Прайс-лист</span>
						<span class="catalog-index__price-title">Скачайте полный прайс</span>
						<span class="catalog-index__price-btn"><span class="glipf-download"></span>Скачать</span>
					</span>
				</a>
			</aside>
		</div>
	</div>
</section>
