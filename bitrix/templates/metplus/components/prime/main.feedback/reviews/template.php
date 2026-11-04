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
            <input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
            <div class="form-consent">
                <?php include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/auth_consent.php'; ?>
            </div>
            <input type="submit" name="submit" value="Отправить" class="form-static_submit-btn main-btn">
        </div>
    </div>
</form>
