<footer class="main-footer">
    <div class="container">
        <div class="footer-shell">
            <div class="footer-rail">
                <div class="footer-brand">
                    <a class="footer-brand__logo" href="/" aria-label="Металлинвест Инокс">
                        <img src="<?=SITE_TEMPLATE_PATH?>/img/static/logo-header-white.svg" width="220" height="28" alt="Металлинвест Инокс">
                    </a>
                    <p class="footer-brand__tag">Нержавейка и цветной металлопрокат · поставки со склада</p>
                </div>
                <div class="footer-column footer-column--contacts">
                    <a class="footer-phone" href="tel:+74951140102">
                        <span class="glipf-call-answer" aria-hidden="true"></span>+7 (495) 114-01-02
                    </a>
                    <ul class="footer-contact_list">
                        <li>
                            <a href="mailto:info@miinox.ru" class="footer-mail"><span class="glipf-email"></span>info@miinox.ru</a>
                        </li>
                        <li class="footer-hours">
                            <span class="glipf-clock" aria-hidden="true"></span>
                            <span class="footer-hours__text">
                                <span class="footer-hours__line">Будни: 8:00–17:00</span>
                                <span class="footer-hours__line footer-hours__line--off">Сб–Вс — выходные</span>
                            </span>
                        </li>
                    </ul>
                    <div class="footer-social">
                        <?/* временно сети КМИ; позже заменить на miinox */?>
                        <a class="footer-social_link" href="https://vk.com/metplusvrn" rel="nofollow" target="_blank" aria-label="ВКонтакте">
                            <img width="40" height="40" src="<?=SITE_TEMPLATE_PATH?>/img/static/social-vk.svg?v=2" alt="ВКонтакте">
                        </a>
                        <a class="footer-social_link" href="https://t.me/Metallinvest36" rel="nofollow" target="_blank" aria-label="Telegram">
                            <img width="40" height="40" src="<?=SITE_TEMPLATE_PATH?>/img/static/social-telegram.svg?v=2" alt="Telegram">
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-main row">
                <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom.menu", Array(
                    "ROOT_MENU_TYPE" => "bottom",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "36000000",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_THEME" => "site",
                    "CACHE_SELECTED_ITEMS" => "N",
                    "MENU_CACHE_GET_VARS" => "",
                    "MAX_LEVEL" => "2",
                    "CHILD_MENU_TYPE" => "bottom_left",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "COMPONENT_TEMPLATE" => "catalog_horizontal"
                ),
                    false
                );?>

                <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_useful.menu", Array(
                    "ROOT_MENU_TYPE" => "bottom_useful",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "36000000",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_THEME" => "site",
                    "CACHE_SELECTED_ITEMS" => "N",
                    "MENU_CACHE_GET_VARS" => "",
                    "MAX_LEVEL" => "1",
                    "CHILD_MENU_TYPE" => "bottom_useful",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                    "COMPONENT_TEMPLATE" => "catalog_horizontal"
                ),
                    false
                );?>
            </div>

            <div class="logo-prime">
                <a href="https://prime-ltd.su/?from=https://miinox.ru/" target="_blank" rel="nofollow">
                    <img src="<?=SITE_TEMPLATE_PATH?>/img/static/prime-logo-white.svg" width="170" height="37" alt="Prime">
                </a>
            </div>

            <div class="footer-legal">
                <div class="footer-legal__row">
                    <div class="rules">
                        © 2006–2026. ООО «Металлинвест Инокс» <br>
                        <a href="/upload/politics.pdf" target="_blank">Политика конфиденциальности</a>
                        <a href="/upload/compliance.pdf" target="_blank">Согласие на обработку персональных данных</a>
                    </div>
                </div>
                <hr class="footer-legal__rule">
                <noindex><p class="footer-legal__cookies">На нашем сайте осуществляется сбор персональных данных и <a target="_blank" href="/upload/politics.pdf">cookies</a> для улучшения работы сайта, персонализации контента и анализа посещаемости. Продолжая пользоваться сайтом, вы соглашаетесь с использованием cookies и <a target="_blank" href="/upload/compliance.pdf">обработкой ваших данных</a> в соответствии с нашей <a target="_blank" href="/upload/politics.pdf">Политикой конфиденциальности</a>. Чтобы отказаться от обработки, отключите сохранение cookies в настройках вашего браузера.</p></noindex>
            </div>
        </div>
    </div>
</footer>
<!-- end main-footer -->

<div class="scroll-to-top"></div>
</div>
<!-- END GLOBAL-WRAPPER -->
<div class="cart-content">
    <div class="cart-content_first"></div>
    <div class="cart-content_second"></div>
</div>

<div aria-hidden="true" class="modal fade js-modal" id="citySelect" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-city" role="document">
        <div class="modal-content">
            <div class="modal-city_title section-title">Выберите ваш город</div>
            <button aria-label="Close" class="close uhified_close-btn" data-dismiss="modal" type="button"></button>
            <div class="row">
                <ul class="modal-city_list-unstyled col-sm-6">
                    <li>Москва</li>
                    <li>Воронеж</li>
                </ul>
                <ul class="modal-city_list-unstyled col-sm-6">
                    <li>Старый Оскол</li>
                    <li>Лиски</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" class="modal fade js-modal" id="callback" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-callback" role="document">
        <div class="modal-content">
            <button aria-label="Close" class="close uhified_close-btn" data-dismiss="modal" type="button"></button>

            <?$APPLICATION->IncludeComponent(
                "prime:main.feedback",
                "call",
                array(
                    "EVENT_MESSAGE_ID" => array(
                        0 => "86",
                    ),
                    "IBLOCK_ID" => "31",
                    "IBLOCK_TYPE" => "feedback",
                    "OK_TEXT" => "Спасибо, ваше сообщение принято.",
                    "PROPERTY_CODE" => array(
                        0 => "NAME",
                        1 => "PHONE",
                    ),
                    "COMPONENT_TEMPLATE" => "call"
                ),
                false
            );?>
        </div>
    </div>
</div>

<? if($_REQUEST["success"]): ?>
    <div aria-hidden="true" class="modal fade js-modal" id="success_msg" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-callback" role="document">
            <div class="modal-content">
                <button aria-label="Close" class="close uhified_close-btn" data-dismiss="modal" type="button"></button>
                <div class="form-callback_title">
                    Сообщение отправлено!
                    <small>Мы обязательно <span class="min">вам перезвоним.</span></small>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>

<link href="<?=SITE_TEMPLATE_PATH?>/libs/jquery-ui/jquery-ui.css" rel="stylesheet" />

<script src="<?=SITE_TEMPLATE_PATH?>/js/min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/libs/fancyTable.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/libs/parallax.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/libs/jquery.sticky-kit.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/libs/jquery.cookie.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/libs/jquery-ui/jquery-ui.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/js/main.js?v=parallax-on"></script>

</body>
</html>
