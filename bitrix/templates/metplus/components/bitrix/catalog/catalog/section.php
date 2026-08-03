<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);

$isFilter = ($arParams['USE_FILTER'] == 'Y');
if ($isFilter)
{
    $arFilter = array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ACTIVE" => "Y",
        "CNT_ACTIVE" => "Y",
        "GLOBAL_ACTIVE" => "Y",
        "ELEMENT_SUBSECTIONS" => "Y"
    );
    if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
        $arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
    elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
        $arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];

    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
    {
        $arCurSection = $obCache->GetVars();
    }
    elseif ($obCache->StartDataCache())
    {
        $arCurSection = array();
        if (Loader::includeModule("iblock"))
        {
            $dbRes = CIBlockSection::GetList(array(), $arFilter, true, array(
                "ID",
                "NAME",
                "IBLOCK_SECTION_ID",
                "DEPTH_LEVEL",
                "DESCRIPTION",
                "UF_*",
            ));

            if(defined("BX_COMP_MANAGED_CACHE"))
            {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                if ($arCurSection = $dbRes->Fetch())
                    $CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);

                $CACHE_MANAGER->EndTagCache();
            }
            else
            {
                if(!$arCurSection = $dbRes->Fetch())
                    $arCurSection = array();
            }
        }
        $obCache->EndDataCache($arCurSection);
    }
    if (!isset($arCurSection))
        $arCurSection = array();
}

if($arCurSection['IBLOCK_SECTION_ID']){
    $dbResStock = CIBlockSection::GetList(array(), ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ID" => $arCurSection['IBLOCK_SECTION_ID']], true, array(
        "ID",
        "NAME",
        "UF_IN_STOCK",
        "UF_PRELOADER",
    ));
    if ($arStockSection = $dbResStock->Fetch())
        $arCurSection['UF_IN_STOCK'] = $arStockSection['UF_IN_STOCK'];
}

if(isset($_COOKIE["in_stock_$arCurSection[ID]"]))
    $arCurSection['UF_IN_STOCK'] = "1";
?>

    <section class="subcategory-section">
        <div class="container">
            <div class="row">

                <div class="catalog_left-column">
                    <aside class="sidebar-catalog">
                        <div class="sidebar-catalog_content">
                            <div class="sidebar-title catalog_sidebar-title">Каталог продукции</div>
                            <?$APPLICATION->IncludeComponent("bitrix:menu", "catalog.menu", Array(
                                "ROOT_MENU_TYPE" => "left",	// Тип меню для первого уровня
                                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                "MENU_CACHE_TYPE" => "A",	// Тип кеширования
                                "MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
                                "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                                "MENU_THEME" => "site",	// Тема меню
                                "CACHE_SELECTED_ITEMS" => "N",
                                "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                                "MAX_LEVEL" => "2",	// Уровень вложенности меню
                                "CHILD_MENU_TYPE" => "",	// Тип меню для остальных уровней
                                "USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                                "DELAY" => "N",	// Откладывать выполнение шаблона меню
                                "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                                "COMPONENT_TEMPLATE" => ""
                            ),
                                false
                            );?>
                        </div>
                        <div class="tablet-hidden">
                            <div class="price-list_box">
                                <div class="price-list_box-content">
                                    <img alt="alt" src="<?=SITE_TEMPLATE_PATH?>/img/static/price-list_img.jpg">
                                    <a href="/prays/price_metall.xls" class="download-price_btn main-btn"><span class="glipf-download"></span>Скачать прайс</a>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="catalog_right-column <?=(!$arCurSection['UF_IN_STOCK']) ? 'to-order' : null?>">

                        <?
                        if(!$arCurSection['UF_IN_STOCK'] && empty($arCurSection['UF_PRELOADER'])){
                            $APPLICATION->IncludeFile($templateFolder . "/include/to_order.php", [
                                "ID" => $arCurSection['ID']
                            ], Array(
                                "SHOW_BORDER" => false,
                            ));
                        }
                        ?>

                        <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumb", Array(
                                "SITE_ID" => SITE_ID
                        ),
                            false
                        );?>


                           <? if(intval($arCurSection['ELEMENT_CNT']) > 0): ?>
                            <div class="unified-text-section top">
                                <?=$arCurSection['UF_DESCRIPTION_TOP'];?>
                            </div>
                            <? endif; ?>


                        <?$APPLICATION->IncludeComponent(
                            "bitrix:catalog.section.list",
                            "section", array(
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                            "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
                            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                            "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
                            "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
                            "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                            "ADD_SECTIONS_CHAIN" => "N"
                        ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        );

                        ?>

                        <? if(intval($arCurSection['ELEMENT_CNT']) > 0): ?>
                        <div class="catalog-section-header">
                            <?$APPLICATION->IncludeComponent("bitrix:catalog.smart.filter", "filter", array(
                                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                    "SECTION_ID" => $arCurSection['ID'],
                                    "FILTER_NAME" => $arParams["FILTER_NAME"],
                                    "PRICE_CODE" => $arParams["~PRICE_CODE"],
                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                    "SAVE_IN_SESSION" => "N",
                                    "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                                    "XML_EXPORT" => "N",
                                    "SECTION_TITLE" => "NAME",
                                    "SECTION_DESCRIPTION" => "DESCRIPTION",
                                    'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                                    "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                                    "SEF_MODE" => $arParams["SEF_MODE"],
                                    "SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
                                    "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                                    "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                                ),
                                    $component,
                                    array('HIDE_ICONS' => 'Y')
                                );
                            ?>
                        </div>
                        <? else: ?>
                            <div class="row form-content_mod" style="padding: 0 1em;">
                                <div class="col-lg-12 form-content_mod-desc">
                                    <div class="form-content_title title-h3">
                                        На текущий момент данных товаров нет, но мы можем привезти под заказ.
                                        Для заказа заполните форму ниже и наши менеджеры свяжутся с Вами.
                                    </div>
                                    <span class="top-small_text"></span>
                                    <?$APPLICATION->IncludeComponent(
                                        "prime:main.feedback",
                                        "reviews",
                                        Array(
                                            "CAPTCHA_SERVER_KEY" => "6Ld60c4UAAAAAP7qkcYtAQ_byWeHtD0kgGFiH0Q9",
                                            "CAPTCHA_SITE_KEY" => "6Ld60c4UAAAAAGXC83w4_ZPy-Q6OErFzaVYjjNQl",
                                            "COMPONENT_TEMPLATE" => "reviews",
                                            "EVENT_MESSAGE_ID" => array(0 => "89"),
                                            "IBLOCK_ID" => "30",
                                            "IBLOCK_TYPE" => "feedback",
                                            "OK_TEXT" => "Спасибо, ваше сообщение принято.",
                                            "PROPERTY_CODE" => array(0=>"NAME",1=>"PHONE",2=>"EMAIL",),
                                            "USE_CAPTCHA" => "Y"
                                        )
                                    );?>
                                </div>
                            </div>
                        <? endif;?>

                        <?
                        $intSectionID = $APPLICATION->IncludeComponent(
                            "bitrix:catalog.section",
                            "element", array(
                            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                            "SECTION_USER_FIELDS" => array('UF_*'),
                            "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                            "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                            "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                            "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                            "PROPERTY_CODE" => (isset($arParams["LIST_PROPERTY_CODE"]) ? $arParams["LIST_PROPERTY_CODE"] : []),
                            "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
                            "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                            "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                            "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                            "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                            "INCLUDE_SUBSECTIONS" => ($arCurSection['UF_INCLUDE_SUBSECTIONS']) ? "N" : $arParams["INCLUDE_SUBSECTIONS"],
							"DEPTH_LEVEL" => $arCurSection['DEPTH_LEVEL'],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                            "FILTER_NAME" => $arParams["FILTER_NAME"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "SET_TITLE" => $arParams["SET_TITLE"],
                            "MESSAGE_404" => $arParams["~MESSAGE_404"],
                            "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                            "SHOW_404" => $arParams["SHOW_404"],
                            "FILE_404" => $arParams["FILE_404"],
                            "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                            "PAGE_ELEMENT_COUNT" => $arCurSection["UF_COUNT"] ?: $arParams["PAGE_ELEMENT_COUNT"],
                            "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                            "PRICE_CODE" => $arParams["~PRICE_CODE"],
                            "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                            "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

                            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                            "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                            "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                            "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                            "PRODUCT_PROPERTIES" => (isset($arParams["PRODUCT_PROPERTIES"]) ? $arParams["PRODUCT_PROPERTIES"] : []),

                            "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                            "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                            "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                            "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                            "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                            "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                            "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                            "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                            "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                            "LAZY_LOAD" => $arParams["LAZY_LOAD"],
                            "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
                            "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

                            "OFFERS_CART_PROPERTIES" => (isset($arParams["OFFERS_CART_PROPERTIES"]) ? $arParams["OFFERS_CART_PROPERTIES"] : []),
                            "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                            "OFFERS_PROPERTY_CODE" => (isset($arParams["LIST_OFFERS_PROPERTY_CODE"]) ? $arParams["LIST_OFFERS_PROPERTY_CODE"] : []),
                            "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                            "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                            "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                            "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                            "OFFERS_LIMIT" => (isset($arParams["LIST_OFFERS_LIMIT"]) ? $arParams["LIST_OFFERS_LIMIT"] : 0),

                            "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                            "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                            "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                            'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                            'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

                            'LABEL_PROP' => $arParams['LABEL_PROP'],
                            'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                            'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                            'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                            'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                            'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
                            'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
                            'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
                            'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
                            'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
                            'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
                            'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

                            'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                            'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
                            'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                            'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
                            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                            'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
                            'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
                            'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
                            'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
                            'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
                            'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                            'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
                            'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
                            'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                            'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

                            'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
                            'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
                            'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

                            'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                            "ADD_SECTIONS_CHAIN" => "Y",
                            'ADD_TO_BASKET_ACTION' => $basketAction,
                            'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                            'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                            'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                            'USE_COMPARE_LIST' => 'Y',
                            'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
                            'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
                            'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : '')
                        ),
                            $component
                        );
                        ?>

					<? if($arCurSection['DEPTH_LEVEL'] > 1): ?>
					<div class="unified-text-section">
						<?=$arCurSection['DESCRIPTION'];?>
					</div>
					<? endif; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <?
                            /* TAGS LIST */
                            $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH."/inc/tags_list.php", Array("SECTION" => $arCurSection), Array(
                                "MODE"      => "php",
                                "NAME"      => "",
                                "TEMPLATE"  => "",
                                "SHOW_BORDER" => false,
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?$APPLICATION->IncludeComponent("prime:news.line", "accordion", Array(
                                "ACTIVE_DATE_FORMAT" => "d.M.Y",	// Формат показа даты
                                "CACHE_GROUPS" => "Y",	// Учитывать права доступа
                                "CACHE_TIME" => "300",	// Время кеширования (сек.)
                                "CACHE_TYPE" => "A",	// Тип кеширования
                                "DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
                                "FIELD_CODE" => array(	// Поля
                                    0 => "DETAIL_TEXT",
                                    1 => "",
                                ),
                                "ID" => $arCurSection['UF_ACCORDION'],
                                "IBLOCKS" => array(	// Код информационного блока
                                    0 => "35",
                                ),
                                "IBLOCK_TYPE" => "components",	// Тип информационного блока
                                "NEWS_COUNT" => "100",	// Количество новостей на странице
                                "SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
                                "SORT_BY2" => "ID",	// Поле для второй сортировки новостей
                                "SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
                                "SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
                            ),
                                false
                            );?>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </section>
    <!-- end category-section -->

<? if(false): ?>
<div class="form-content">
    <div class="container">
        <div class="form-content_title title-h3">Остались вопросы? <span class="blue-color">Задайте</span> их прямо сейчас</div>
        <span class="top-small_text">Отправьте заявку и специалист свяжется с Вами</span>
        <?$APPLICATION->IncludeComponent("prime:main.feedback", "catalog", Array(
            "EVENT_MESSAGE_ID" => array(	// Почтовые шаблоны для отправки письма
                0 => "87",
            ),
            "IBLOCK_ID" => "33",	// Код информационного блока
            "IBLOCK_TYPE" => "feedback",	// Тип информационного блока (используется только для проверки)
            "OK_TEXT" => "Спасибо, ваше сообщение принято.",	// Сообщение, выводимое пользователю после отправки
            "PROPERTY_CODE" => array(	// Поля формы
                0 => "NAME",
                1 => "PHONE",
                2 => "EMAIL",
                3 => "THEME",
            ),
            "USE_CAPTCHA" => "Y",
            "CAPTCHA_SITE_KEY" => "6Ld60c4UAAAAAGXC83w4_ZPy-Q6OErFzaVYjjNQl",
            "CAPTCHA_SERVER_KEY" => "6Ld60c4UAAAAAP7qkcYtAQ_byWeHtD0kgGFiH0Q9",
        ),
            false
        );?>
    </div>
</div>
<!-- end form-content -->
<? endif; ?>

