<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "О компании, ее история, инфографика и план развития");
$APPLICATION->SetPageProperty("title", "О компании «Металлинвест Инокс»");
$APPLICATION->SetTitle("О компании");
?><main class="main-content">
<div class="inner-page_title-section">
	<div class="container">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"breadcrumb",
	Array(
		"SITE_ID" => SITE_ID
	)
);?>
		<h1>О компании</h1>
	</div>
</div>
 <!-- end inner-page_title-section -->
<div class="about-us_section">
	<div class="container">
		<div class="title-h3">
			 Общая информация о компании
		</div>
		<div class="row">
			<div class="about-us_left-column">
				 <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/inc/about_us.php',
                    Array(),
                    Array("MODE" => "html",)
                );?>
			</div>
			<div class="about-us_right-column">
				<div class="about-us_img">
					 <img alt="alt" src="/upload/medialibrary/2ee/2eef062f917044e3cb18751eab76f653.jpg">
				</div>
			</div>
		</div>
	</div>
</div>
 <!-- end about-us_section --> <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"about.digit",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "about.digit",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_TEXT",2=>"",),
		"IBLOCKS" => array(0=>"14",),
		"IBLOCK_TYPE" => "about",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC"
	)
);?> <!-- end digit-section --> <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"about.history",
	Array(
		"ACTIVE_DATE_FORMAT" => "j M Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "about.history",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_TEXT",2=>"DATE_ACTIVE_FROM",3=>"",),
		"IBLOCKS" => array(0=>"15",),
		"IBLOCK_TYPE" => "about",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC"
	)
);?> <!-- end history-company_section --> <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"about.advantages",
	Array(
		"ACTIVE_DATE_FORMAT" => "j M Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "about.advantages",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"CODE",1=>"NAME",2=>"PREVIEW_TEXT",3=>"",),
		"IBLOCKS" => array(0=>"16",),
		"IBLOCK_TYPE" => "about",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC"
	)
);?> <!-- end advantages-mod_section --> <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"about.galery",
	Array(
		"ACTIVE_DATE_FORMAT" => "j M Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "about.galery",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_PICTURE",2=>"",),
		"IBLOCKS" => array(0=>"17",),
		"IBLOCK_TYPE" => "about",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC"
	)
);?> <!-- end photo-galery_section -->
<div class="current-vacancies_section">
	<div class="container">
		<div class="row align-items-center">
			<div class="current-vacancies_left">
				<div class="current-vacancies_img">
 <img alt="alt" src="/bitrix/templates/metplus/img/static/current-vacancies.png" data-src="/bitrix/templates/metplus/img/static/current-vacancies.png">
				</div>
			</div>
			<div class="current-vacancies_right">
				<div class="top-text">
					 Хотите работать у нас?
				</div>
				<div class="current-vacancies_title">
					 Посмотрите актуальные <span class="min blue-color">вакансии</span>
				</div>
 <a href="/vacancy/" class="main-btn">Смотреть вакансии</a>
			</div>
		</div>
	</div>
</div>
 <!-- end current-vacancies_section --> </main>
    <!-- end main-content --><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>