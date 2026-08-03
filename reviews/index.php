<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Отзывы о компании Металлинвест");
$APPLICATION->SetPageProperty("keywords", "отзывы");
$APPLICATION->SetPageProperty("description", "На этой странице сайта Вы можете ознакомиться с отзывами о компании «КОРПОРАЦИЯ Металлинвест»");
$APPLICATION->SetTitle("Отзывы");
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
		<h1>Отзывы</h1>
	</div>
</div>
 <!-- end inner-page_title-section --> <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"reviews.partner",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "reviews.galery",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_TEXT",2=>"PREVIEW_PICTURE",3=>"",),
		"IBLOCKS" => array(0=>"20",),
		"IBLOCK_TYPE" => "reviews",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC"
	)
);?> <!-- end partners_section --> <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"reviews.galery",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "300",
		"CACHE_TYPE" => "A",
		"DETAIL_URL" => "",
		"FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_PICTURE",2=>"",),
		"IBLOCKS" => array(0=>"19",),
		"IBLOCK_TYPE" => "reviews",
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC"
	)
);?> <!-- end reviews-section -->
<div class="form-content form-content_mod">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-4">
				<div class="beam-img">
 <img alt="alt" src="/bitrix/templates/metplus/img/static/87n1ueywt8ujo642eddn3m531kcwsvy2.png" data-src="/bitrix/templates/metplus/img/static/87n1ueywt8ujo642eddn3m531kcwsvy2.png">
				</div>
			</div>
			<div class="col-lg-8 form-content_mod-desc">
				<div class="form-content_title title-h3">
					 Ваши предложения и пожелания руководителю компании
				</div>
 <span class="top-small_text"></span>
				<?$APPLICATION->IncludeComponent(
	"prime:main.feedback",
	"reviews",
	Array(
		"CAPTCHA_SERVER_KEY" => "6Ld60c4UAAAAAP7qkcYtAQ_byWeHtD0kgGFiH0Q9",
		"CAPTCHA_SITE_KEY" => "6Ld60c4UAAAAAGXC83w4_ZPy-Q6OErFzaVYjjNQl",
		"COMPONENT_TEMPLATE" => "reviews",
		"EVENT_MESSAGE_ID" => array(0=>"84",),
		"IBLOCK_ID" => "30",
		"IBLOCK_TYPE" => "feedback",
		"OK_TEXT" => "Спасибо, ваше сообщение принято.",
		"PROPERTY_CODE" => array(0=>"NAME",1=>"PHONE",2=>"EMAIL",),
		"USE_CAPTCHA" => "Y"
	)
);?>
			</div>
		</div>
	</div>
</div>
 <!-- end form-content --> </main>
    <!-- end main-content --><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>