<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "ООО «КОРПОРАЦИЯ МЕТАЛЛИНВЕСТ» предлагает купить изделия из металлопроката в Москве по ценам производителя! Широкий ассортимент продукции в нашем каталоге!");
$APPLICATION->SetPageProperty("title", "Металлопрокат в Москве от ООО «КОРПОРАЦИЯ МЕТАЛЛИНВЕСТ» | Низкие цены от поставщиков | Купить металлопрокат в Москве и Московской области ");
$APPLICATION->SetTitle("ООО «КОРПОРАЦИЯ МЕТАЛЛИНВЕСТ» - изделия из металлопроката");
?>
<main class="main-content">

        <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"slider_new",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "slider_new",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_PICTURE",2=>"",),
		"PROPERTY_CODE" => array(
			0 => "IMG_DESKTOP",
			1 => "IMG_TABLET",
			2 => "IMG_MOBILE",
			3 => "LINK",
			4 => "ADS",
		),
		"IBLOCKS" => array(0=>"5",),
		"IBLOCK_TYPE" => "components",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC"
	)
);?>
        <!-- end main-section -->
        <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"main",
	Array(
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COUNT_ELEMENTS" => "N",
		"FILTER_NAME" => "sectionsFilter",
		"IBLOCK_ID" => "40",
		"IBLOCK_TYPE" => "1c_catalog",
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array(0=>"",1=>"",),
		"SECTION_ID" => "",
		"SECTION_URL" => "/catalog/#SECTION_CODE#/",
		"SECTION_USER_FIELDS" => array(0=>"UF_ICON",1=>"",),
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "1",
		"VIEW_MODE" => "LINE",
        "SECTION_USER_FIELDS" => array("UF_*")
	)
);?>
        <!-- end category-section -->
        <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"services",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"PREVIEW_PICTURE",1=>"PREVIEW_TEXT",),
		"IBLOCKS" => array(0=>"4",),
		"IBLOCK_TYPE" => "news",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC"
	)
);?>
        <!-- end services-section -->
        <div class="advantages-section">
            <div class="container">
                <div class="section-title">НАШИ ПРЕИМУЩЕСТВА</div>
                <div class="row">
                    <div class="left-column">
                        <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"slider.advantages",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "advantages",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"CODE",1=>"NAME",2=>"PREVIEW_TEXT",3=>"PREVIEW_PICTURE",),
		"IBLOCKS" => array(0=>"7",),
		"IBLOCK_TYPE" => "components",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC"
	)
);?>
                    </div>
                    <div class="right-column">
                        <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"advantages",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "advantages",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"CODE",1=>"NAME",2=>"PREVIEW_TEXT",3=>"",),
		"IBLOCKS" => array(0=>"6",),
		"IBLOCK_TYPE" => "components",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC"
	)
);?>
                    </div>
                </div>
            </div>
        </div>
        <!-- end advantages-section -->




 <!-- Бренды в движении - начало -->

<style>
.brands {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  background-color: #000;
  padding: 20px 0 10px;
}

.brands-row {
  width: 100%;
  overflow: hidden;
  position: relative;
}

.brands-track {
  display: flex;
  width: max-content;
  animation: scroll-left 50s linear infinite;
}

.bottom-row .brands-track {
  animation: scroll-right 50s linear infinite;
}

.brand-item {
  flex: 0 0 280px;
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #222;
  background-color: #000;
  box-sizing: border-box;
  transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease, filter .2s ease;
}

.brand-item--wide {
  flex: 0 0 380px;
}

.brand-item:hover {
  transform: translateY(-6px);
  border-color: #f8f8f8;
  box-shadow:
    0 12px 24px rgba(248, 248, 248, 0.25),
    0 0 0 1px rgba(248, 248, 248, 0.2);
  filter: brightness(1.15);
  z-index: 2;
}

.brand-item img {
  display: block;
  max-width: 74%;
  max-height: 56%;
  width: auto;
  height: auto;
  object-fit: contain;
  opacity: 0.95;
}

.brand-item--wide img {
  max-width: 88%;
  max-height: 48%;
}

.brand-item a {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
}

.brands-header {
  color: #fff;
  text-align: center;
  font-size: 2.25rem;
  font-weight: 800;
  line-height: 1.1;
  margin-bottom: 1.5em;
  margin-top: 1em;
  text-transform: uppercase;
}

@keyframes scroll-left {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}

@keyframes scroll-right {
  from { transform: translateX(-50%); }
  to { transform: translateX(0); }
}

@media (max-width: 768px) {
  .brand-item {
    flex: 0 0 180px;
    height: 96px;
  }
  .brand-item--wide {
    flex: 0 0 260px;
  }
}

.brands-link {
  color: #fff;
  text-transform: uppercase;
  text-decoration: underline;
  text-align: center;
  margin: 50px auto;
  width: auto;
}

.brands-link:hover {
  color: #45aaee;
  transition: color 0.2s linear;
  text-decoration: underline;
}
</style>

<?php
$partnersTpl = SITE_TEMPLATE_PATH . '/img/static/partners';
$partnerItems = '
  <div class="brand-item"><img src="' . $partnersTpl . '/mechel.svg" alt="Мечел"></div>
  <div class="brand-item"><img src="' . $partnersTpl . '/severstal.svg" alt="Северсталь"></div>
  <div class="brand-item"><img src="' . $partnersTpl . '/vtb.svg" alt="ВТБ"></div>
  <div class="brand-item brand-item--wide">
    <a href="https://marcegaglia.ru/ru/" target="_blank" rel="noopener">
      <img src="' . $partnersTpl . '/marcegaglia.png" alt="Marcegaglia">
    </a>
  </div>
  <div class="brand-item"><img src="' . $partnersTpl . '/kmi.svg" alt="KMI"></div>
';
?>

<div class="brands">
  <div class="brands-header">Наши партнеры</div>

  <div class="brands-row top-row">
    <div class="brands-track"><?=$partnerItems?></div>
  </div>

  <div class="brands-row bottom-row">
    <div class="brands-track"><?=$partnerItems?></div>
  </div>

  <a class="brands-link" target="_blank" href="/reviews/">Узнать больше</a>
</div>


<script src="/bitrix/js/brands.js"></script>

 <!-- Бренды в движении - конец -->



        <div class="text-section" data-parallax="scroll" data-position="top" data-bleed="10" data-natural-width="1917" data-natural-height="1159" data-image-src="<?=SITE_TEMPLATE_PATH?>/img/bg/text-section_bg.jpg">
            <div class="container">
                <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/inc/main_desc.php',
                    Array(),
                    Array("MODE" => "html",)
                );?>
            </div>
        </div>
        <!-- end text-section -->
        <div class="map-container">
            <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"contact.main",
	Array(
		"ADD_SECTIONS_CHAIN" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COUNT_ELEMENTS" => "N",
		"FILTER_NAME" => "sectionsFilter",
		"IBLOCK_ID" => "18",
		"IBLOCK_TYPE" => "contact",
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array(0=>"",1=>"",),
		"SECTION_ID" => "",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "2",
		"VIEW_MODE" => "LINE"
	)
);?>
            <div id="map"></div>
        </div>
    </main>
    <!-- end main-content --><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
