<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><!DOCTYPE html>
<html class="no-js" lang="<?=LANGUAGE_ID?>">
<head>
    <meta content="<?=SITE_TEMPLATE_PATH?>/browserconfig.xml" name="msapplication-config" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?$APPLICATION->ShowTitle()?></title>
    <link rel="icon" href="<?=SITE_DIR?>favicon.ico?v=catalog" sizes="any">
    <link rel="icon" type="image/svg+xml" href="<?=SITE_TEMPLATE_PATH?>/img/static/favicon.svg?v=catalog">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=SITE_TEMPLATE_PATH?>/img/static/favicon-32x32.png?v=catalog">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=SITE_TEMPLATE_PATH?>/img/static/favicon-16x16.png?v=catalog">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=SITE_TEMPLATE_PATH?>/img/static/apple-touch-icon.png?v=catalog">
    <link rel="mask-icon" href="<?=SITE_TEMPLATE_PATH?>/img/static/safari-pinned-tab.svg?v=catalog" color="#206380">
    <link rel="manifest" href="<?=SITE_DIR?>manifest.json?v=catalog">
    <meta name="theme-color" content="#206380">
    <meta name="msapplication-TileColor" content="#206380">

    <? $APPLICATION->ShowHead(); ?>
    <link href="<?=SITE_TEMPLATE_PATH?>/css/min.css?v=w1630b" rel="stylesheet" />
    <link href="<?=SITE_TEMPLATE_PATH?>/css/main.css?v=mi-burger4" rel="stylesheet" />

</head>
<body>
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<!--[if lt IE 10]>
<p class="browsehappy"><br>Вы используете <strong>устаревший</strong> браузер.
    Пожалуйста, <a href="http://browsehappy.com/">обновите его</a> для корректного
    отображения сайтов.</p>
<![endif]-->
<div class="mi-global-wrapper">
    <div class="mi-wrapper-loader">
        <div class="preloader">
            <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var loader = document.querySelector('.mi-wrapper-loader');
            if (loader) loader.style.display = 'none';
        });
    </script>
    <header class="ui-header">
        <div class="mi-main-head">
            <div class="container">
                <div class="main-head__bar">
                    <div class="mi-main-head_left-column">
                        <div class="mi-head-logo">
                            <a href="/" title="Металлинвест Инокс">
                                <img class="head-logo_mark" src="<?=SITE_TEMPLATE_PATH?>/img/static/logo-mark.svg?v=1" alt="" width="66" height="36">
                                <span class="head-logo_text">Металлинвест&nbsp;Инокс</span>
                            </a>
                        </div>
                        <div class="mi-head_phone-box mi-tablet-small_hidden">
                            <a href="tel:+74732475555" class="mi-head_phone-number">+7 (473) 247-55-55</a>
                        </div>
                        <div class="mi-head_opening-hours mi-tablet-small_hidden" aria-label="График работы">
                            <span class="head_opening-hours__line"><strong>Пн–Пт</strong> 8:00–17:00</span>
                            <span class="head_opening-hours__line head_opening-hours__line_muted">Сб–Вс выходной</span>
                        </div>
                        <div class="mi-head_select-city mi-tablet-small_hidden">
                            <a href="#citySelect" data-toggle="modal" class="mi-select-city_btn">Выберите город:</a>
                            <div class="mi-select-city_field">Воронеж</div>
                        </div>
                    </div>

                    <div class="mi-main-head_right-column">
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

                        <div class="hamburger mi-hamburger--spring mi-fixed-menu_hamburger">
                            <div class="mi-hamburger-box">
                                <div class="mi-hamburger-inner"></div>
                            </div>
                        </div>

                        <div class="main-head__search">
                            <?$APPLICATION->IncludeComponent("bitrix:search.title", "search.title", Array(
                                "CATEGORY_0" => array(
                                    0 => "iblock_1c_catalog",
                                ),
                                "CATEGORY_0_TITLE" => "",
                                "CATEGORY_0_iblock_1c_catalog" => array(
                                    0 => "40",
                                ),
                                "IBLOCK_ID" => "40",
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
                        </div>

                        <?php
                        global $USER;
                        $lkHref = (is_object($USER) && $USER->IsAuthorized()) ? '/personal/' : '/login/';
                        ?>
                        <a class="head-account" href="<?= htmlspecialcharsbx($lkHref) ?>" title="Личный кабинет" aria-label="Личный кабинет">
                            <svg class="head-account__ico" viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="8" r="3.4"></circle>
                                <path d="M5.5 19.2c.9-3.2 3.3-4.8 6.5-4.8s5.6 1.6 6.5 4.8"></path>
                            </svg>
                        </a>
                        <div class="mi-head-cart">
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
                        <div class="hamburger mi-hamburger--spring mi-tablet-hamburger">
                            <div class="mi-hamburger-box">
                                <div class="mi-hamburger-inner"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="mi-head-nav">
            <div class="container">
                <div class="mi-mobile-visible head-nav__search">
                    <?$APPLICATION->IncludeComponent("bitrix:search.title", "search.title.mobile", Array(
                        "CATEGORY_0" => array(
                            0 => "iblock_1c_catalog",
                        ),
                        "CATEGORY_0_TITLE" => "",
                        "CATEGORY_0_iblock_1c_catalog" => array(
                            0 => "40",
                        ),
                        "IBLOCK_ID" => "40",
                        "IBLOCK_TYPE" => "1c_catalog",
                        "CHECK_DATES" => "N",
                        "CONTAINER_ID" => "title-search-mobile",
                        "INPUT_ID" => "title-search-mobile-input",
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
                </div>
                <?
					$IS_HIDDEN_MENU = true;
					$curDir = $APPLICATION->GetCurDir();
					$show_catalog_menu = \Bitrix\Main\Config\Option::get("grain.customsettings", "show_catalog_menu");
					$links_show_catalog_menu_arr = preg_split("/[\s,]+/", $show_catalog_menu);
					
					if(in_array($curDir, $links_show_catalog_menu_arr))
						$IS_HIDDEN_MENU = false;
				
					$APPLICATION->IncludeComponent("bitrix:menu", "top.menu", Array(
                    "ROOT_MENU_TYPE" => "top",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "36000000",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_THEME" => "site",
                    "CACHE_SELECTED_ITEMS" => "N",
                    "IBLOCK_ID" => "40",
                    "MENU_CACHE_GET_VARS" => "",
                    "MAX_LEVEL" => "3",
                    "CHILD_MENU_TYPE" => "left",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "COMPONENT_TEMPLATE" => "catalog_horizontal",
					"IS_HIDDEN_MENU" => $IS_HIDDEN_MENU,
                ),
                    false
                );?>
                <div class="mi-tablet-visible">
                    <a href="#citySelect" data-toggle="modal" class="mi-head_select-city mi-head_select-city_mobile">
                        <span class="select-city_label">Город</span>
                        <span class="mi-select-city_field">Воронеж</span>
                    </a>
                </div>
                <div class="mi-tablet-small_visible">
                    <div class="mi-head_phone-box">
                        <a href="tel:+74732475555" class="mi-head_phone-number">+7 (473) 247-55-55</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- END UI-HEADER -->
