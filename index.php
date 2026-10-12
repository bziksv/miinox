<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "ООО «КОРПОРАЦИЯ МЕТАЛЛИНВЕСТ» предлагает купить изделия из металлопроката в Москве по ценам производителя! Широкий ассортимент продукции в нашем каталоге!");
$APPLICATION->SetPageProperty("title", "Металлопрокат в Москве от ООО «КОРПОРАЦИЯ МЕТАЛЛИНВЕСТ» | Низкие цены от поставщиков | Купить металлопрокат в Москве и Московской области ");
$APPLICATION->SetTitle("ООО «КОРПОРАЦИЯ МЕТАЛЛИНВЕСТ» - изделия из металлопроката");
?>
<main class="main-content main-content_home" data-home-version="new">

        <aside class="home-tune" id="home-tune" aria-label="Настройки главной">
            <button type="button" class="home-tune__fab" id="home-tune-fab" aria-expanded="false" aria-controls="home-tune-panel">
                <span class="home-tune__fab-hint">Варианты</span>
                <span class="home-tune__fab-val" data-home-tune-summary>Новая · Бренд</span>
            </button>
            <div class="home-tune__panel" id="home-tune-panel" hidden>
                <div class="home-tune__group">
                    <div class="home-tune__label">Главная</div>
                    <div class="home-tune__row" role="group" aria-label="Версия главной">
                        <button type="button" class="home-tune__chip" data-home-version="old">Старая</button>
                        <button type="button" class="home-tune__chip is-active" data-home-version="new">Новая</button>
                    </div>
                </div>
                <div class="home-tune__group" data-home-tune-new-only>
                    <div class="home-tune__label">Пункты внутри</div>
                    <div class="home-tune__items" role="group" aria-label="Вид пунктов">
                        <button type="button" class="home-tune__theme is-active" data-hd-items="rows">
                            <span class="home-tune__theme-name">Строки</span>
                            <span class="home-tune__theme-desc">каждый пункт — кликабельная строка</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-items="cards">
                            <span class="home-tune__theme-name">Карточки</span>
                            <span class="home-tune__theme-desc">категория в отдельной плашке</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-items="chips">
                            <span class="home-tune__theme-name">Чипы</span>
                            <span class="home-tune__theme-desc">подпункты кнопками-плашками</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-items="directory">
                            <span class="home-tune__theme-name">Справочник</span>
                            <span class="home-tune__theme-desc">точки + больше воздуха</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-items="list">
                            <span class="home-tune__theme-name">Список</span>
                            <span class="home-tune__theme-desc">как в макете</span>
                        </button>
                    </div>
                </div>
                <div class="home-tune__group" data-home-tune-new-only>
                    <div class="home-tune__label">Услуги</div>
                    <div class="home-tune__items" role="group" aria-label="Вид блока услуг">
                        <button type="button" class="home-tune__theme is-active" data-svc-layout="quad">
                            <span class="home-tune__theme-name">4 в ряд</span>
                            <span class="home-tune__theme-desc">ровная сетка без дырки</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-svc-layout="tiles">
                            <span class="home-tune__theme-name">2×2</span>
                            <span class="home-tune__theme-desc">крупные квадраты</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-svc-layout="mosaic">
                            <span class="home-tune__theme-name">Мозаика</span>
                            <span class="home-tune__theme-desc">первая крупная + три справа</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-svc-layout="bands">
                            <span class="home-tune__theme-name">Полосы</span>
                            <span class="home-tune__theme-desc">горизонтальные карточки</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-svc-layout="classic">
                            <span class="home-tune__theme-name">Классика</span>
                            <span class="home-tune__theme-desc">старая сетка 3+1</span>
                        </button>
                    </div>
                </div>
                <div class="home-tune__group" data-home-tune-new-only>
                    <div class="home-tune__label">Цвет / стиль</div>
                    <div class="home-tune__themes" role="group" aria-label="Цветовой вариант">
                        <button type="button" class="home-tune__theme is-active" data-hd-theme="brand" title="Бренд">
                            <span class="home-tune__swatch" style="--a:#d7e6ee;--b:#206380;--c:#f0e4d8;--d:#8a5a3a"></span>
                            <span class="home-tune__theme-name">Бренд</span>
                            <span class="home-tune__theme-desc">#206380 + тёплая бронза</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-theme="flat" title="Без переливов">
                            <span class="home-tune__swatch" style="--a:#c8dae5;--b:#206380;--c:#e9d7c6;--d:#8a5a3a"></span>
                            <span class="home-tune__theme-name">Без переливов</span>
                            <span class="home-tune__theme-desc">как бренд, но ровный фон</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-theme="graphite" title="Графит">
                            <span class="home-tune__swatch" style="--a:#eceff2;--b:#4a5560;--c:#efe8e1;--d:#6b5344"></span>
                            <span class="home-tune__theme-name">Графит</span>
                            <span class="home-tune__theme-desc">сталь / бронза, сдержанно</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-theme="paper" title="Бумага">
                            <span class="home-tune__swatch" style="--a:#f5f7f8;--b:#206380;--c:#faf6f1;--d:#9a6b45"></span>
                            <span class="home-tune__theme-name">Бумага</span>
                            <span class="home-tune__theme-desc">светло, акцент в кнопках</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-theme="ink" title="Чернила">
                            <span class="home-tune__swatch" style="--a:#173a4a;--b:#8ec4d8;--c:#3a2418;--d:#e0b089"></span>
                            <span class="home-tune__theme-name">Чернила</span>
                            <span class="home-tune__theme-desc">тёмные панели, светлый текст</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-theme="soft" title="Мягкий">
                            <span class="home-tune__swatch" style="--a:#e8eef2;--b:#3a6f86;--c:#f3ebe3;--d:#a67c5d"></span>
                            <span class="home-tune__theme-name">Мягкий</span>
                            <span class="home-tune__theme-desc">приглушённые тона</span>
                        </button>
                        <button type="button" class="home-tune__theme" data-hd-theme="mock" title="Макет">
                            <span class="home-tune__swatch" style="--a:#9fb8cc;--b:#35627d;--c:#d08c5c;--d:#a04c1c"></span>
                            <span class="home-tune__theme-name">Макет</span>
                            <span class="home-tune__theme-desc">как в HTML-концепте</span>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

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

        <div class="home-version home-version_new" data-home-pane="new">
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
        </div>

        <div class="home-version home-version_old" data-home-pane="old" hidden>
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
		"SECTION_USER_FIELDS" => array("UF_*"),
		"SHOW_PARENT_NAME" => "Y",
		"TOP_DEPTH" => "1",
		"VIEW_MODE" => "LINE",
	)
);?>
        </div>

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

<style>
.brands {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  background-color: #000;
  padding: 20px 0 10px;
}
.brands-row { width: 100%; overflow: hidden; position: relative; }
.brands-track { display: flex; width: max-content; animation: scroll-left 50s linear infinite; }
.bottom-row .brands-track { animation: scroll-right 50s linear infinite; }
.brand-item {
  flex: 0 0 280px; height: 120px;
  display: flex; align-items: center; justify-content: center;
  border: 1px solid #222; background-color: #000; box-sizing: border-box;
  transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease, filter .2s ease;
}
.brand-item--wide { flex: 0 0 380px; }
.brand-item:hover {
  transform: translateY(-6px); border-color: #f8f8f8;
  box-shadow: 0 12px 24px rgba(248,248,248,.25), 0 0 0 1px rgba(248,248,248,.2);
  filter: brightness(1.15); z-index: 2;
}
.brand-item img { display: block; max-width: 74%; max-height: 56%; width: auto; height: auto; object-fit: contain; opacity: .95; }
.brand-item--wide img { max-width: 88%; max-height: 48%; }
.brand-item a { display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; }
.brands-header {
  color: #fff; text-align: center; font-size: 2.25rem; font-weight: 800;
  line-height: 1.1; margin-bottom: 1.5em; margin-top: 1em; text-transform: uppercase;
}
@keyframes scroll-left { from { transform: translateX(0); } to { transform: translateX(-50%); } }
@keyframes scroll-right { from { transform: translateX(-50%); } to { transform: translateX(0); } }
@media (max-width: 768px) {
  .brand-item { flex: 0 0 180px; height: 96px; }
  .brand-item--wide { flex: 0 0 260px; }
}
.brands-link { color: #fff; text-transform: uppercase; text-decoration: underline; text-align: center; margin: 50px auto; width: auto; }
.brands-link:hover { color: #45aaee; transition: color .2s linear; text-decoration: underline; }
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
  <div class="brands-row top-row"><div class="brands-track"><?=$partnerItems?></div></div>
  <div class="brands-row bottom-row"><div class="brands-track"><?=$partnerItems?></div></div>
  <a class="brands-link" target="_blank" href="/reviews/">Узнать больше</a>
</div>

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
