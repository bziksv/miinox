<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><!DOCTYPE html>
<html class="no-js" lang="<?=LANGUAGE_ID?>">
<head>
    <meta content="<?=SITE_TEMPLATE_PATH?>/browserconfig.xml" name="msapplication-config" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?$APPLICATION->ShowTitle()?></title>
    <link href="<?=SITE_DIR?>favicon.ico" rel="icon" type="image/x-icon" />
    <link href="<?=SITE_TEMPLATE_PATH?>/img/static/apple-touch-icon.png" rel="apple-touch-icon" />

    <? $APPLICATION->ShowHead(); ?>
    <link href="<?=SITE_TEMPLATE_PATH?>/css/min.css?v=w1630b" rel="stylesheet" />
    <link href="<?=SITE_TEMPLATE_PATH?>/css/main.css?v=brand83" rel="stylesheet" />
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
                            <a href="/" title="Металлинвест Инокс">
                                <img class="head-logo_mark" src="<?=SITE_TEMPLATE_PATH?>/img/static/logo-mark.svg?v=1" alt="" width="66" height="36">
                                <span class="head-logo_text">Металлинвест&nbsp;Инокс</span>
                            </a>
                        </div>
                        <div class="head_phone-box tablet-small_hidden">
                            <a href="tel:+74952128506" class="head_phone-number">+7 (495) 212-85-06</a>
                        </div>
                        <div class="head_select-city tablet-small_hidden">
                            <a href="#citySelect" data-toggle="modal" class="select-city_btn">Выберите город:</a>
                            <div class="select-city_field">Москва</div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6 col-sm-7 col-4 main-head_right-column">
                        <?$APPLICATION->IncludeComponent("bitrix:menu", "catalog.menu.fixed", Array(
                            "ROOT_MENU_TYPE" => "left",
                            "MENU_CACHE_TYPE" => "A",
                            "MENU_CACHE_TIME" => "36000000",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "MENU_THEME" => "site",
                            "CACHE_SELECTED_ITEMS" => "N",
                            "MENU_CACHE_GET_VARS" => "",
                            "MAX_LEVEL" => "3",
                            "CHILD_MENU_TYPE" => "left",
                            "USE_EXT" => "Y",
                            "DELAY" => "N",
                            "ALLOW_MULTI_SELECT" => "N",
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
                            "CATEGORY_0" => array(
                                0 => "iblock_1c_catalog",
                            ),
                            "CATEGORY_0_TITLE" => "",
                            "CATEGORY_0_iblock_1c_catalog" => array(
                                0 => "39",
                            ),
                            "IBLOCK_ID" => "39",
                            "IBLOCK_TYPE" => "1c_catalog",
                            "CHECK_DATES" => "N",
                            "CONTAINER_ID" => "title-search",
                            "INPUT_ID" => "title-search-input",
                            "NUM_CATEGORIES" => "1",
                            "ORDER" => "rank",
                            "PAGE" => "/",
                            "SHOW_INPUT" => "Y",
                            "SHOW_OTHERS" => "N",
                            "TOP_COUNT" => "5",
                            "USE_LANGUAGE_GUESS" => "N",
                        ),
                            false
                        );?>

                        <div class="head-cart">
                            <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "basket.small", Array(
                                "HIDE_ON_BASKET_PAGES" => "N",
                                "PATH_TO_BASKET" => "/cart/",
                                "POSITION_FIXED" => "N",
                                "SHOW_AUTHOR" => "N",
                                "SHOW_EMPTY_VALUES" => "Y",
                                "SHOW_NUM_PRODUCTS" => "Y",
                                "SHOW_PERSONAL_LINK" => "N",
                                "SHOW_PRODUCTS" => "N",
                                "SHOW_REGISTRATION" => "N",
                                "SHOW_TOTAL_PRICE" => "N",
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
                    "IBLOCK_ID" => "39",
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
                        <span class="select-city_field">Москва</span>
                    </div>
                </div>
                <div class="tablet-small_visible">
                    <div class="head_phone-box">
                        <a href="tel:+74952128506" class="head_phone-number">+7 (495) 212-85-06</a>
                    </div>
                </div>
                <div class="mobile-visible">
                    <?$APPLICATION->IncludeComponent("bitrix:search.title", "search.title.mobile", Array(
                        "CATEGORY_0" => array(	// Ограничение области поиска
                            0 => "iblock_1c_catalog",
                        ),
                        "CATEGORY_0_TITLE" => "",	// Название категории
                        "CATEGORY_0_iblock_1c_catalog" => array(
                            0 => "39",
                        ),
                        "IBLOCK_ID" => "39",
                        "IBLOCK_TYPE" => "1c_catalog",
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
