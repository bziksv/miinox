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
		"IBLOCK_ID" => "39",
		"IBLOCK_TYPE" => "1c_catalog",
		"SECTION_CODE" => "1_1_nerzhaveyushchaya_stal",
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
  padding: 20px 0;
}

.brands-row {
  width: 100%;
  overflow: visible;;
  position: relative;
}

.brands-track {
  display: flex;
  animation: scroll-left 45s linear infinite;
}

.bottom-row .brands-track {
  animation: scroll-right 45s linear infinite;
}

.brand-item {
  flex: 0 0 240px;
  height: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #222;
  background-color: #000;
}

.brand-item:hover {
    transform: translateY(-10px);
    border-color: #f8f8f8;
    box-shadow: 
        0 15px 30px rgba(248, 248, 248, 0.4),
        0 0 0 1px rgba(248, 248, 248, 0.3);
    filter: brightness(1.2);
    z-index: 1000;
}

.brand-item img {
  max-width: 80%;
  max-height: 80%;
  object-fit: contain;
  opacity: 0.8;
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

/* Бесшовная анимация */
@keyframes scroll-left {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}

@keyframes scroll-right {
  from { transform: translateX(-50%); }
  to { transform: translateX(0); }
}

/* 📱 Адаптив */
@media (max-width: 768px) {
  .brand-item {
    flex: 0 0 160px;
    height: 100px;
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
		transition: linear color 0.2s;
		text-decoration: underline;
	}

</style>

<div class="brands">
	<div class="brands-header">Наши партнеры</div>
 <!-- Верхняя строка - движется влево -->

  <div class="brands-row top-row">
    <div class="brands-track">
		<div class="brand-item"><img src="/upload/logo-amet.svg" alt="Амет"></div>
      <div class="brand-item"><img src="/upload/evraz.svg" alt="Evraz"></div>
      <div class="brand-item"><img src="/upload/mmk.svg" alt="Магнитогорский металлургический комбинат"></div>
      <div class="brand-item"><img src="/upload/mechel.svg" alt="Mechel"></div>
    </div>
  </div>

 <!-- Нижняя строка - движется вправо -->

  <div class="brands-row bottom-row">
    <div class="brands-track">
		<div class="brand-item"><img height="80px" src="/upload/nlmk.svg" alt="НЛМК"></div>
      <div class="brand-item"><img src="/upload/severstal.svg" alt="Северсталь"></div>
      <div class="brand-item"><img src="/upload/vtb.svg" alt="ВТБ"></div>
    </div>
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
