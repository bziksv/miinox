<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><!DOCTYPE html>
<html class="no-js" lang="<?=LANGUAGE_ID?>">
<head>
    <meta content="<?=SITE_TEMPLATE_PATH?>/browserconfig.xml" name="msapplication-config" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?$APPLICATION->ShowTitle()?></title>
    <link href="<?=SITE_DIR?>favicon.ico" rel="icon" type="image/png" />

    <? $APPLICATION->ShowHead(); ?>
    <style>body{opacity: 0;}</style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

<? if(isDebug()): ?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(85336879, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/85336879" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->


    <script type="text/javascript" src="https://incut.prime-ltd.su/incut/incut.js" async></script>
    <link rel="stylesheet" href="https://incut.prime-ltd.su/incut/incut.css">
<? endif; ?>

</head>
<body>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<!--[if lt IE 10]>
<p class="browsehappy"><br>Вы используете <strong>устаревший</strong> браузер.
    Пожалуйста, <a href="http://browsehappy.com/">обновите его</a> для корректного
    отображения сайтов.</p>
<![endif]-->
<div class="global-wrapper">
    <div class="wrapper-loader">
        <div class="preloader">
            <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
        </div>
    </div>
    <header class="ui-header">
        <div class="main-head">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-md-6 col-sm-5 col-8 main-head_left-column">
                        <div class="head-logo">
                            <a href="/"><img data-src="<?=SITE_TEMPLATE_PATH?>/img/static/logo.png" alt="alt"></a>
                        </div>
                        <div class="head_phone-box tablet-small_hidden">
                            <a href="tel:+74732475555" class="head_phone-number">+7 (473) 247-55-55</a>
                            <!--<div class="head_callback-btn">
                                <a href="#callback" data-toggle="modal">
                                    <span class="glipf-call-answer"></span>
                                    <span class="text">Заказать звонок</span>
                                </a>
                            </div>-->
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6 col-sm-7 col-4 main-head_right-column">
                        <div class="head_opening-hours">
                           <!-- <strong>График работы:</strong>
                            <span class="min">Будни: 8:00 - 19:00</span>
                            Выходные: 9.00 - 17:00
							-->
                        </div>
                        <div class="head_select-city">
                            <a href="#citySelect" data-toggle="modal" class="select-city_btn">Выберите город:</a>
                            <div class="select-city_field">Москва</div>
                        </div>

                        <?$APPLICATION->IncludeComponent("bitrix:menu", "catalog.menu.fixed", Array(
                            "ROOT_MENU_TYPE" => "left",	// Тип меню для первого уровня
                            "MENU_CACHE_TYPE" => "A",	// Тип кеширования
                            "MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
                            "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                            "MENU_THEME" => "site",	// Тема меню
                            "CACHE_SELECTED_ITEMS" => "N",
                            "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                            "MAX_LEVEL" => "2",	// Уровень вложенности меню
                            "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
                            "USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                            "DELAY" => "N",	// Откладывать выполнение шаблона меню
                            "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                            "COMPONENT_TEMPLATE" => "catalog_horizontal"
                        ),
                            false
                        );?>

                        <div class="hamburger hamburger--spring fixed-menu_hamburger">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>

                        <?$APPLICATION->IncludeComponent("bitrix:search.title", "search.title", Array(
                            "CATEGORY_0" => array(	// Ограничение области поиска
                                0 => "iblock_catalog",
                            ),
                            "CATEGORY_0_TITLE" => "",	// Название категории
                            "CATEGORY_0_iblock_catalog" => array(	// Искать в информационных блоках типа "iblock_catalog"
                                0 => "13",
                            ),
                            "IBLOCK_ID" => "13",
                            "IBLOCK_TYPE" => "catalog",
                            "CHECK_DATES" => "N",	// Искать только в активных по дате документах
                            "CONTAINER_ID" => "title-search",	// ID контейнера, по ширине которого будут выводиться результаты
                            "INPUT_ID" => "title-search-input",	// ID строки ввода поискового запроса
                            "NUM_CATEGORIES" => "1",	// Количество категорий поиска
                            "ORDER" => "rank",	// Сортировка результатов
                            "PAGE" => "/",	// Страница выдачи результатов поиска (доступен макрос #SITE_DIR#)
                            "SHOW_INPUT" => "Y",	// Показывать форму ввода поискового запроса
                            "SHOW_OTHERS" => "N",	// Показывать категорию "прочее"
                            "TOP_COUNT" => "5",	// Количество результатов в каждой категории
                            "USE_LANGUAGE_GUESS" => "N",	// Включить автоопределение раскладки клавиатуры
                        ),
                            false
                        );?>

                        <div class="head-cart">
                            <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "basket.small", Array(
                                "HIDE_ON_BASKET_PAGES" => "N",	// Не показывать на страницах корзины и оформления заказа
                                "POSITION_FIXED" => "N",	// Отображать корзину поверх шаблона
                                "SHOW_AUTHOR" => "N",	// Добавить возможность авторизации
                                "SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
                                "SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
                                "SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
                                "SHOW_PRODUCTS" => "N",	// Показывать список товаров
                                "SHOW_REGISTRATION" => "N",	// Добавить возможность регистрации
                                "SHOW_TOTAL_PRICE" => "N",	// Показывать общую сумму по товарам
                                "COMPONENT_TEMPLATE" => ".default_old"
                            ),
                                false
                            );?>
                        </div>
                        <div class="hamburger hamburger--spring tablet-hamburger">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="head-nav">
            <div class="container">
                <?
					$IS_HIDDEN_MENU = true;
					$curDir = $APPLICATION->GetCurDir();
					$show_catalog_menu = \Bitrix\Main\Config\Option::get("grain.customsettings", "show_catalog_menu");
					$links_show_catalog_menu_arr = preg_split("/[\s,]+/", $show_catalog_menu);
					
					if(in_array($curDir, $links_show_catalog_menu_arr))
						$IS_HIDDEN_MENU = false;
				
					$APPLICATION->IncludeComponent("bitrix:menu", "top.menu", Array(
                    "ROOT_MENU_TYPE" => "top",	// Тип меню для первого уровня
                    "MENU_CACHE_TYPE" => "A",	// Тип кеширования
                    "MENU_CACHE_TIME" => "36000000",	// Время кеширования (сек.)
                    "MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
                    "MENU_THEME" => "site",	// Тема меню
                    "CACHE_SELECTED_ITEMS" => "N",
                    "IBLOCK_ID" => "13",
                    "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
                    "MAX_LEVEL" => "3",	// Уровень вложенности меню
                    "CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
                    "USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
                    "DELAY" => "N",	// Откладывать выполнение шаблона меню
                    "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
                    "COMPONENT_TEMPLATE" => "catalog_horizontal",
					"IS_HIDDEN_MENU" => $IS_HIDDEN_MENU,
                ),
                    false
                );?>
                <div class="tablet-visible">
                    <div class="head_select-city head_select-city_mobile">
                        <a href="#citySelect" data-toggle="modal" class="select-city_btn">Выберите город:</a>
                        <div class="select-city_field">Москва</div>
                    </div>
                </div>
                <div class="tablet-small_visible">
                    <div class="head_phone-box">
                        <a href="tel:+74951140102" class="head_phone-number">+7 (495) 114-01-02</a>
                    </div>
                </div>
                <div class="mobile-visible">
                    <?$APPLICATION->IncludeComponent("bitrix:search.title", "search.title.mobile", Array(
                        "CATEGORY_0" => array(	// Ограничение области поиска
                            0 => "iblock_catalog",
                        ),
                        "CATEGORY_0_TITLE" => "",	// Название категории
                        "CATEGORY_0_iblock_catalog" => array(	// Искать в информационных блоках типа "iblock_catalog"
                            0 => "13",
                        ),
                        "IBLOCK_ID" => "13",
                        "IBLOCK_TYPE" => "catalog",
                        "CHECK_DATES" => "N",	// Искать только в активных по дате документах
                        "CONTAINER_ID" => "title-search-mobile",	// ID контейнера, по ширине которого будут выводиться результаты
                        "INPUT_ID" => "title-search-mobile-input",	// ID строки ввода поискового запроса
                        "NUM_CATEGORIES" => "1",	// Количество категорий поиска
                        "ORDER" => "rank",	// Сортировка результатов
                        "PAGE" => "/",	// Страница выдачи результатов поиска (доступен макрос #SITE_DIR#)
                        "SHOW_INPUT" => "Y",	// Показывать форму ввода поискового запроса
                        "SHOW_OTHERS" => "N",	// Показывать категорию "прочее"
                        "TOP_COUNT" => "5",	// Количество результатов в каждой категории
                        "USE_LANGUAGE_GUESS" => "N",	// Включить автоопределение раскладки клавиатуры
                    ),
                        false
                    );?>
                </div>
            </div>
        </nav>
    </header>
    <!-- END UI-HEADER -->
