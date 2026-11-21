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
    <div class="mi-cart-content_header">
        <div class="mi-cart-content_title">Оформление заказа</div>
        <button type="button" class="mi-cart-close" aria-label="Закрыть"></button>
        <ul class="mi-cart-steps">
            <li class="mi-cart-step_item active">Шаг 1</li>
            <li class="mi-cart-step_item active">Шаг 2</li>
            <li class="mi-cart-step_item">Шаг 3</li>
        </ul>
    </div>
    <form action="<?=POST_FORM_ACTION_URI?>" method="POST" class="mi-checkout-form">
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="component" value="order">
        <div class="mi-cart-content_body">
            <?if(!empty($arResult["ERROR_MESSAGE"]))
            {
                foreach($arResult["ERROR_MESSAGE"] as $v)
                    ShowError($v);
            }
            ?>
            <div class="row">
                <? foreach ($arResult['FIELD'] as $field):?>
                <div class="col-md-6">
                    <div class="mi-form-group">
                        <input type="<?=($field['DESCRIPTION']) ?: 'text'?>" class="mi-form-input" name="<?=$field['CODE']?>" value="<?=htmlspecialcharsbx((string)($field['VALUE'] ?? ''))?>" placeholder="<?=$field['NAME']?><?if($field['REQUIED'] == 'Y'):?>*<?endif;?>" <?if($field['REQUIED'] == 'Y'):?>required<?endif;?>>
                    </div>
                </div>
                <? endforeach; ?>
                <div class="col-md-12">
                    <div class="mi-form-group mi-form-group_last">
                        <textarea class="mi-form-textarea" name="COMMENT" placeholder="Ваш комментарий"></textarea>
                    </div>
                </div>
            </div>
			<div class="checkout-consent">
				<?php include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/auth_consent.php'; ?>
			</div>

        </div>

        <div class="mi-cart-content_footer mi-cart-content_footer-second align-items-center">
            <div class="mi-left-column">
                <a href="" class="mi-gray-btn mi-back-site_btn js_back-site">Вернуться</a>
            </div>
            <div class="mi-right-column">
                <input type="submit" class="mi-main-btn mi-form-checkout_btn js-checkout-2"  value="Оформить заказ">
            </div>
        </div>
    </form>
</div>
