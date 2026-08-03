<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
?>

<?if(!empty($arResult["ERROR_MESSAGE"]))
{
	foreach($arResult["ERROR_MESSAGE"] as $v)
		ShowError($v);
}
?>

<form action="<?=POST_FORM_ACTION_URI?>" method="POST" enctype="multipart/form-data" class="static-form">
    <?=bitrix_sessid_post()?>

    <div class="row">
        <? foreach($arResult['USER_FIELD'] as $field):?>
        <div class="col-md-4">
            <div class="form-group">
                <input type="<?=($field['CODE'] == 'PHONE') ? 'tel' : 'text'?>"
                       class="form-input"
                       name="<?=$field['CODE']?>"
                       value="<?=$arResult[$field['CODE']]?>"
                       placeholder="<?=$field['NAME']?> <?if($field['IS_REQUIRED'] == "Y"):?>*<?endif?>"
                       <?if($field['IS_REQUIRED'] == "Y"):?>required<?endif?>
                >
            </div>
        </div>
        <? endforeach; ?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <textarea name="PREVIEW_TEXT" class="form-textarea" placeholder="Введите текст сообщения *" required><?=$arResult['PREVIEW_TEXT']?></textarea>
            </div>
        </div>

        <div class="col-md-4">
            <?if($arParams["USE_CAPTCHA"] == "Y"):?>
                <div class="g-recaptcha" data-sitekey="<?=$arParams["CAPTCHA_SITE_KEY"]?>"></div>
            <?endif;?>

            <input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
            <input type="submit" name="submit" value="Отправить" class="form-static_submit-btn main-btn">

            <span class="form-static_policy-text">
Нажимая кнопку вы соглашаетесь с 
<a href="/upload/politics.pdf" target="_blank">политикой конфиденциальности</a>
и 
<a href="/upload/compliance.pdf" target="_blank">правилами обработки персональных данных</a>
</span>
        </div>
    </div>
</form>
