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
        <? foreach($arResult['USER_FIELD'] as $field):
            if($field['PROPERTY_TYPE'] == "F")
                continue;
            ?>
        <div class="col-sm-6">
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
    <div class="form-group">
        <textarea name="PREVIEW_TEXT" class="form-textarea" placeholder="Краткое описание к резюме*" required><?=$arResult['PREVIEW_TEXT']?></textarea>
    </div>

    <div class="row form-static_footer">
        <? foreach($arResult['USER_FIELD'] as $field):
        if($field['PROPERTY_TYPE'] == "F"):
        ?>
        <div class="col-sm-6">
            <label class="input-file">
                <span class="input-file_icon glipf-paper-clip"></span>
                <span class="button">
                    <input type="file" name="<?=$field['CODE']?>" multiple="" class="requiredField callback-file" onchange="this.parentNode.nextSibling.value = this.value" tabindex="0">
                </span><input class="input-file-text" placeholder="<?=$field['NAME']?>">
            </label>
        </div>
        <?
        endif;
        endforeach; ?>
        <div class="col-sm-6 static-form_right-column">
            <input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
            <input type="submit" name="submit" value="Выслать резюме" class="form-static_submit-btn form-static_submit-btn--mod main-btn">
        </div>
    </div>
</form>
