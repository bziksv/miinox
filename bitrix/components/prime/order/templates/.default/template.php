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
                        <input type="<?=($field['DESCRIPTION']) ?: 'text'?>" class="form-input" name="<?=$field['CODE']?>" value="<?=htmlspecialcharsbx((string)($field['VALUE'] ?? ''))?>" placeholder="<?=$field['NAME']?><?if($field['REQUIED'] == 'Y'):?>*<?endif;?>" <?if($field['REQUIED'] == 'Y'):?>required<?endif;?>>
                    </div>
                </div>
                <? endforeach; ?>
                <div class="col-md-12">
                    <div class="form-group form-group_last">
                        <textarea class="form-textarea" name="COMMENT" placeholder="Ваш комментарий"></textarea>
                    </div>
                </div>
            </div>
			<div class="checkout-consent">
				<?php include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/auth_consent.php'; ?>
			</div>

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
