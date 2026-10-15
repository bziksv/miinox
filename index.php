<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "ООО «КОРПОРАЦИЯ МЕТАЛЛИНВЕСТ» предлагает купить изделия из металлопроката в Москве по ценам производителя! Широкий ассортимент продукции в нашем каталоге!");
$APPLICATION->SetPageProperty("title", "Металлопрокат в Москве от ООО «КОРПОРАЦИЯ МЕТАЛЛИНВЕСТ» | Низкие цены от поставщиков | Купить металлопрокат в Москве и Московской области ");
$APPLICATION->SetTitle("ООО «КОРПОРАЦИЯ МЕТАЛЛИНВЕСТ» - изделия из металлопроката");
?>
<main class="main-content main-content_home" data-home-version="new">

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

        <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"home_dual",
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
		"SECTION_USER_FIELDS" => array("UF_*"),
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "3",
		"VIEW_MODE" => "LINE",
	)
);?>

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

        <div class="text-section">
            <div class="container">
                <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/inc/main_desc.php',
                    Array(),
                    Array("MODE" => "html",)
                );?>
            </div>
        </div>

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
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
