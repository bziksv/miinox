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
$this->setFrameMode(true);
?>
<div class="container">
    <div class="cart-content_header">
        <div class="cart-content_title">Оформление заказа</div>
        <button type="button" class="cart-close" aria-label="Закрыть"></button>
        <ul class="cart-steps">
            <li class="cart-step_item active">Шаг 1</li>
            <li class="cart-step_item active">Шаг 2</li>
            <li class="cart-step_item">Шаг 3</li>
        </ul>
    </div>
    <form action="<?=POST_FORM_ACTION_URI?>" method="POST" class="checkout-form">
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="component" value="order">
        <div class="cart-content_body">
            <?if(!empty($arResult["ERROR_MESSAGE"]))
            {
                foreach($arResult["ERROR_MESSAGE"] as $v)
                    ShowError($v);
            }
            ?>
            <div class="row">
                <? foreach ($arResult['FIELD'] as $field):?>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="<?=($field['DESCRIPTION']) ?: 'text'?>" class="form-input" name="<?=$field['CODE']?>" placeholder="<?=$field['NAME']?><?if($field['REQUIED'] == 'Y'):?>*<?endif;?>" <?if($field['REQUIED'] == 'Y'):?>required<?endif;?>>
                    </div>
                </div>
                <? endforeach; ?>
                <div class="col-md-12">
                    <div class="form-group form-group_last">
                        <textarea class="form-textarea" name="COMMENT" placeholder="Ваш комментарий"></textarea>
                    </div>
                </div>
            </div>
			<noindex><p style="margin-top: 15px;">На нашем сайте осуществляется сбор персональных данных и <span style="color: #073e71;"><a target="_blank" href="/upload/politika-ispolzovanija-cookies-metplus-vrn.pdf">cookies</a></span> для улучшения работы сайта, персонализации контента и анализа посещаемости. Продолжая, вы соглашаетесь с использованием cookies и <span style="color: #073e71;"><a target="_blank" href="/upload/compliance.pdf">обработкой ваших данных</a></span> в соответствии с нашей <span style="color: #073e71;"><a target="_blank" href="/upload/politics.pdf">Политикой конфиденциальности</a></span>.</p></noindex>

        </div>

        <div class="cart-content_footer cart-content_footer-second align-items-center">
            <div class="left-column">
                <a href="" class="gray-btn back-site_btn js_back-site">Вернуться</a>
            </div>
            <div class="right-column">
                <input type="submit" class="main-btn form-checkout_btn js-checkout-2"  value="Оформить заказ">
            </div>
        </div>
    </form>
</div>
