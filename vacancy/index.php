<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Вакансии компании Металлинвест");
$APPLICATION->SetPageProperty("keywords", "вакансии");
$APPLICATION->SetPageProperty("description", "На этой странице мы публикуем вакансии в компании «КОРПОРАЦИЯ Металлинвест», приходите к нам на работу. Дружный коллектив, честное руководство.");
$APPLICATION->SetTitle("Вакансии");
?>

    <main class="main-content">
        <div class="inner-page_title-section">
            <div class="container">
                <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", Array("SITE_ID" => SITE_ID),
                    false
                );?>
                <h1>Вакансии</h1>
            </div>
        </div>
        <!-- end inner-page_title-section -->
        <div class="vacancy-section">
            <div class="container">
                <?$APPLICATION->IncludeComponent("bitrix:news.line", "vacancy.advatages", Array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
                    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
                    "CACHE_TIME" => "300",	// Время кеширования (сек.)
                    "CACHE_TYPE" => "A",	// Тип кеширования
                    "DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
                    "FIELD_CODE" => array(	// Поля
                        0 => "CODE",
                        1 => "NAME",
                        2 => "PREVIEW_TEXT",
                        3 => "",
                    ),
                    "IBLOCKS" => array(	// Код информационного блока
                        0 => "26",
                    ),
                    "IBLOCK_TYPE" => "vacancy",	// Тип информационного блока
                    "NEWS_COUNT" => "20",	// Количество новостей на странице
                    "SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
                    "SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
                    "SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
                    "SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
                ),
                    false
                );?>


                <?$APPLICATION->IncludeComponent("bitrix:news.line", "vacancy.wrapper", Array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
                    "CACHE_GROUPS" => "Y",	// Учитывать права доступа
                    "CACHE_TIME" => "300",	// Время кеширования (сек.)
                    "CACHE_TYPE" => "A",	// Тип кеширования
                    "DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
                    "FIELD_CODE" => array(	// Поля
                        0 => "NAME",
                        1 => "PREVIEW_TEXT",
                        2 => "DETAIL_TEXT",
                        3 => "",
                    ),
                    "IBLOCKS" => array(	// Код информационного блока
                        0 => "27",
                    ),
                    "IBLOCK_TYPE" => "vacancy",	// Тип информационного блока
                    "NEWS_COUNT" => "20",	// Количество новостей на странице
                    "SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
                    "SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
                    "SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
                    "SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
                    "COMPONENT_TEMPLATE" => "vacancy.advatages"
                ),
                    false
                );?>
                <div class="title-h3">Вы хотите работать с нами? Отправьте свое резюме</div>
                <?$APPLICATION->IncludeComponent(
	"prime:main.feedback",
	"vacancy",
	array(
		"EVENT_MESSAGE_ID" => array(
			0 => "85",
		),
		"IBLOCK_ID" => "32",
		"IBLOCK_TYPE" => "feedback",
		"OK_TEXT" => "Спасибо, ваше сообщение принято.",
		"PROPERTY_CODE" => array(
			0 => "NAME",
			1 => "EMAIL",
			2 => "FILE",
		),
		"COMPONENT_TEMPLATE" => "vacancy"
	),
	false
);?>
            </div>
        </div>
        <!-- end vacancy-section -->
    </main>
    <!-- end main-content -->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
