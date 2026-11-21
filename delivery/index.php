<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Доставляем собственным автотранспортом приобретенные товары в нашей компании. Ознакомиться с условиями и ценами можно на этой странице");
$APPLICATION->SetPageProperty("title", "Доставка продукции клиентам от компании «Корпорация Металлинвест»");
$APPLICATION->SetTitle("delivery");
?>

    <main class="mi-main-content">
        <div class="mi-inner-page_title-section">
            <div class="container">
                <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", Array("SITE_ID" => SITE_ID),
                    false
                );?>
                <h1>Доставка</h1>
            </div>
        </div>
        <!-- end inner-page_title-section -->
        <div class="mi-delivery-section">
            <div class="container">
                <?$APPLICATION->IncludeComponent("bitrix:news.line", "delivery.header", Array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
                    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
                    "CACHE_TIME" => "300",	// Время кеширования (сек.)
                    "CACHE_TYPE" => "A",	// Тип кеширования
                    "DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
                    "FIELD_CODE" => array(	// Поля
                        0 => "CODE",
                        1 => "NAME",
                        2 => "PREVIEW_TEXT",
                        3 => "PREVIEW_PICTURE",
                        4 => "",
                    ),
                    "IBLOCKS" => array(	// Код информационного блока
                        0 => "22",
                    ),
                    "IBLOCK_TYPE" => "delivery",	// Тип информационного блока
                    "NEWS_COUNT" => "20",	// Количество новостей на странице
                    "SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
                    "SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
                    "SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
                    "SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
                ),
                    false
                );?>

                <?$APPLICATION->IncludeComponent("bitrix:news.line", "delivery.transport", Array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
                    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
                    "CACHE_TIME" => "300",	// Время кеширования (сек.)
                    "CACHE_TYPE" => "A",	// Тип кеширования
                    "DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
                    "FIELD_CODE" => array(	// Поля
                        0 => "CODE",
                        1 => "NAME",
                        2 => "PREVIEW_TEXT",
                        3 => "PREVIEW_PICTURE",
                        4 => "",
                    ),
                    "IBLOCKS" => array(	// Код информационного блока
                        0 => "23",
                    ),
                    "IBLOCK_TYPE" => "delivery",	// Тип информационного блока
                    "NEWS_COUNT" => "20",	// Количество новостей на странице
                    "SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
                    "SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
                    "SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
                    "SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
                    "COMPONENT_TEMPLATE" => "delivery.header"
                ),
                    false
                );?>
            </div>
        </div>
        <!-- end delivery-section -->

        <?/*$APPLICATION->IncludeComponent(
            "bitrix:news.line",
            "delivery.order_transport",
            array(
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "300",
                "CACHE_TYPE" => "A",
                "DETAIL_URL" => "",
                "FIELD_CODE" => array(
                    0 => "CODE",
                    1 => "NAME",
                    2 => "PREVIEW_TEXT",
                    3 => "PREVIEW_PICTURE",
                    4 => "",
                ),
                "IBLOCKS" => array(
                    0 => "24",
                ),
                "IBLOCK_TYPE" => "delivery",
                "NEWS_COUNT" => "20",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_BY2" => "SORT",
                "SORT_ORDER1" => "DESC",
                "SORT_ORDER2" => "ASC",
                "COMPONENT_TEMPLATE" => "delivery.order_transport"
            ),
            false
        );*/?>
        <!-- end order-transport_section -->
    </main>
    <!-- end main-content -->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>