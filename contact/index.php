<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "телефон, почта, карты");
$APPLICATION->SetPageProperty("description", "Контакты компании Металлинвест: номера телефонов, почта, карты");
$APPLICATION->SetTitle("Контакты компании Металлинвест");
?>

    <main class="mi-main-content">
        <div class="mi-inner-page_title-section">
            <div class="container">
                <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", Array("SITE_ID" => SITE_ID),
                    false
                );?>
                <h1>Контакты</h1>
            </div>
        </div>
        <!-- end inner-page_title-section -->
        <?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "contact", Array(
            "ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
            "CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
            "CACHE_GROUPS" => "Y",	// Учитывать права доступа
            "CACHE_TIME" => "36000000",	// Время кеширования (сек.)
            "CACHE_TYPE" => "A",	// Тип кеширования
            "COUNT_ELEMENTS" => "N",	// Показывать количество элементов в разделе
            "FILTER_NAME" => "sectionsFilter",	// Имя массива со значениями фильтра разделов
            "IBLOCK_ID" => "18",	// Инфоблок
            "IBLOCK_TYPE" => "contact",	// Тип инфоблока
            "SECTION_CODE" => "",	// Код раздела
            "SECTION_FIELDS" => array(	// Поля разделов
                0 => "",
                1 => "",
            ),
            "SECTION_ID" => "",	// ID раздела
            "SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
            "SECTION_USER_FIELDS" => array(	// Свойства разделов
                0 => "",
                1 => "",
            ),
            "SHOW_PARENT_NAME" => "Y",	// Показывать название раздела
            "TOP_DEPTH" => "1",	// Максимальная отображаемая глубина разделов
            "VIEW_MODE" => "LINE",	// Вид списка подразделов
        ),
            false
        );?>
        <!-- end contact-section -->
        <div class="mi-map-container">
            <div id="map"></div>
        </div>
    </main>
    <!-- end main-content -->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>