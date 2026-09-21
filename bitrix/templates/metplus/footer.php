<footer class="main-footer">
    <div class="container">
        <div class="row">
            <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom.menu", Array(
                "ROOT_MENU_TYPE" => "bottom",   // Тип меню для первого уровня
                "MENU_CACHE_TYPE" => "A",   // Тип кеширования
                "MENU_CACHE_TIME" => "36000000",    // Время кеширования (сек.)
                "MENU_CACHE_USE_GROUPS" => "Y", // Учитывать права доступа
                "MENU_THEME" => "site", // Тема меню
                "CACHE_SELECTED_ITEMS" => "N",
                "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
                "MAX_LEVEL" => "2", // Уровень вложенности меню
                "CHILD_MENU_TYPE" => "bottom_left", // Тип меню для остальных уровней
                "USE_EXT" => "Y",   // Подключать файлы с именами вида .тип_меню.menu_ext.php
                "DELAY" => "N", // Откладывать выполнение шаблона меню
                "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
                "COMPONENT_TEMPLATE" => "catalog_horizontal"
            ),
                false
            );?>

            <?$APPLICATION->IncludeComponent("bitrix:menu", "bottom_useful.menu", Array(
                "ROOT_MENU_TYPE" => "bottom_useful",    // Тип меню для первого уровня
                "MENU_CACHE_TYPE" => "A",   // Тип кеширования
                "MENU_CACHE_TIME" => "36000000",    // Время кеширования (сек.)
                "MENU_CACHE_USE_GROUPS" => "Y", // Учитывать права доступа
                "MENU_THEME" => "site", // Тема меню
                "CACHE_SELECTED_ITEMS" => "N",
                "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
                "MAX_LEVEL" => "1", // Уровень вложенности меню
                "CHILD_MENU_TYPE" => "bottom_useful",   // Тип меню для остальных уровней
                "USE_EXT" => "Y",   // Подключать файлы с именами вида .тип_меню.menu_ext.php
                "DELAY" => "N", // Откладывать выполнение шаблона меню
                "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
                "COMPONENT_TEMPLATE" => "catalog_horizontal"
            ),
                false
            );?>
            <div class="footer-column">
                <ul class="footer-contact_list">
                    <li><a href="tel:+74951140102"><span class="glipf-call-answer"></span>+7 (495) 114-01-02</a></li>
                    <li><a href="mailto:info@miinox.ru" class="footer-mail"><span class="glipf-email"></span>info@miinox.ru</a></li>
                    <li>
                        <span class="glipf-clock"></span>Будни: 8:00 - 17:00 <br>Суббота, воскресенье — выходные
                    </li>
				</ul>

				<p>Мы в социальных сетях:</p>
				<div class="footer-social">
					<a class="footer-social_link" href="https://vk.com/metplusvrn" rel="nofollow" target="_blank" aria-label="ВКонтакте">
						<img width="45" height="45" src="<?=SITE_TEMPLATE_PATH?>/img/static/social-vk.svg?v=2" alt="ВКонтакте">
					</a>
					<a class="footer-social_link" href="https://t.me/Metallinvest36" rel="nofollow" target="_blank" aria-label="Telegram">
						<img width="45" height="45" src="<?=SITE_TEMPLATE_PATH?>/img/static/social-telegram.svg?v=2" alt="Telegram">
					</a>
				</div>
 </div>

 </div>
        <div class="row">
            <div class="col-lg-10">
                <div class="rules">
					© 2006–2026. ООО «Металлинвест Инокс» <br>
                    <a href="/upload/politics.pdf" target="_blank">Политика конфиденциальности</a>
                    <a href="/upload/compliance.pdf" target="_blank">Согласие на обработку персональных данных</a>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="logo-prime">
                    <a href="https://prime-ltd.su/?from=https://miinox.ru/" target="_blank" rel="nofollow">
                        <img src="<?=SITE_TEMPLATE_PATH?>/img/static/prime-logo-white.svg" width="170" height="37" alt="Prime">
                    </a>
                </div>
            </div>
</div>
		<hr style="margin: 25px 0;">
		<noindex><p style="font-size: 0.8rem;">На нашем сайте осуществляется сбор персональных данных и <a target="_blank" href="/upload/politics.pdf">cookies</a> для улучшения работы сайта, персонализации контента и анализа посещаемости. Продолжая пользоваться сайтом, вы соглашаетесь с использованием cookies и <a target="_blank" href="/upload/compliance.pdf">обработкой ваших данных</a> в соответствии с нашей <a target="_blank" href="/upload/politics.pdf">Политикой конфиденциальности</a>. Чтобы отказаться от обработки, отключите сохранение cookies в настройках вашего браузера.</p></noindex>

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
<script src="<?=SITE_TEMPLATE_PATH?>/js/main.js?v=menu-open1"></script>

</body>
</html>
