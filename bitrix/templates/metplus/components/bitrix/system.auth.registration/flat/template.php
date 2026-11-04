<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2024 Bitrix
 */

use Bitrix\Main\Web\Json;

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["SHOW_SMS_FIELD"] == true)
{
	CJSCore::Init('phone_auth');
}

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>
<div class="auth-screen">
<div class="auth-card bx-authform">
<noindex>

<?php
if(!empty($arParams["~AUTH_RESULT"]["MESSAGE"])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
?>
	<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?php endif?>

<?php if($arResult["SHOW_EMAIL_SENT_CONFIRMATION"]):?>
	<div class="alert alert-success"><?= GetMessage("AUTH_EMAIL_SENT")?></div>
<?php endif?>

<?php if(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"] && $arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	<div class="alert alert-warning"><?= GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></div>
<?php endif?>

<?php if($arResult["SHOW_SMS_FIELD"] == true):?>

<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="regform">

	<input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult["SIGNED_DATA"])?>" />

	<div class="bx-authform-formgroup-container">
		<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?= GetMessage("main_register_sms_code")?></div>
		<div class="bx-authform-input-container">
			<input type="text" name="SMS_CODE" maxlength="255" value="<?=htmlspecialcharsbx($arResult["SMS_CODE"] ?? '')?>" autocomplete="off" />
		</div>
	</div>

	<div class="bx-authform-formgroup-container">
		<input type="submit" class="btn btn-primary" name="code_submit_button" value="<?= GetMessage("main_register_sms_send")?>" />
	</div>

</form>

<script>
new BX.PhoneAuth({
	containerId: 'bx_register_resend',
	errorContainerId: 'bx_register_error',
	interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
	data:
		<?= Json::encode([
			'signedData' => $arResult["SIGNED_DATA"],
		]) ?>,
	onError:
		function(response)
		{
			var errorNode = BX('bx_register_error');
			errorNode.innerHTML = '';
			for(var i = 0; i < response.errors.length; i++)
			{
				errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br />';
			}
			errorNode.style.display = '';
		}
});
</script>

<div id="bx_register_error" style="display:none" class="alert alert-danger"></div>

<div id="bx_register_resend"></div>

<?php elseif(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"]):?>

	<h3 class="bx-title">Регистрация</h3>

	<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data">
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="REGISTRATION" />
		<?= bitrix_sessid_post(); ?>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?=GetMessage("AUTH_NAME")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_NAME" maxlength="255" value="<?=$arResult["USER_NAME"]?>" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?=GetMessage("AUTH_LAST_NAME")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_LAST_NAME" maxlength="255" value="<?=$arResult["USER_LAST_NAME"]?>" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_LOGIN_MIN")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["USER_LOGIN"]?>" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_PASSWORD_REQ")?></div>
			<div class="bx-authform-input-container">
<?php if($arResult["SECURE_AUTH"]):?>
				<div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?= GetMessage("AUTH_SECURE_NOTE")?></div></div>

<script>
document.getElementById('bx_auth_secure').style.display = '';
</script>
<?php endif?>
				<input type="password" name="USER_PASSWORD" id="reg-password" maxlength="255" value="<?=$arResult["USER_PASSWORD"]?>" autocomplete="new-password" />
			</div>
			<button type="button" class="auth-pass-gen" id="reg-pass-gen">Сгенерировать пароль</button>
		</div>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_CONFIRM")?></div>
			<div class="bx-authform-input-container">
<?php if($arResult["SECURE_AUTH"]):?>
				<div class="bx-authform-psw-protected" id="bx_auth_secure_conf" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?= GetMessage("AUTH_SECURE_NOTE")?></div></div>

<script>
document.getElementById('bx_auth_secure_conf').style.display = '';
</script>
<?php endif?>
				<input type="password" name="USER_CONFIRM_PASSWORD" id="reg-password-confirm" maxlength="255" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="new-password" />
			</div>
		</div>

<?php if($arResult["EMAIL_REGISTRATION"]):?>
		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?php if($arResult["EMAIL_REQUIRED"]):?><span class="bx-authform-starrequired">*</span><?php endif?><?=GetMessage("AUTH_EMAIL")?></div>
			<div class="bx-authform-input-container">
				<input type="text" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" />
			</div>
		</div>
<?php endif?>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><span class="bx-authform-starrequired">*</span>Телефон</div>
			<div class="bx-authform-input-container">
				<input type="tel" name="USER_PHONE_NUMBER" maxlength="20" value="<?=$arResult["USER_PHONE_NUMBER"]?>" inputmode="tel" autocomplete="tel" placeholder="+7 900 000-00-00" required />
			</div>
		</div>

<?php if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<?php foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container"><?php if ($arUserField["MANDATORY"]=="Y"):?><span class="bx-authform-starrequired">*</span><?php endif?><?=$arUserField["EDIT_FORM_LABEL"]?></div>
			<div class="bx-authform-input-container">
<?php
$APPLICATION->IncludeComponent(
	"bitrix:system.field.edit",
	$arUserField["USER_TYPE"]["USER_TYPE_ID"],
	array(
		"bVarsFromForm" => $arResult["bVarsFromForm"],
		"arUserField" => $arUserField,
		"form_name" => "bform"
	),
	null,
	array("HIDE_ICONS"=>"Y")
);
?>
			</div>
		</div>

	<?php endforeach;?>
<?php endif;?>
<?php if ($arResult["USE_CAPTCHA"] == "Y"):?>
		<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />

		<div class="bx-authform-formgroup-container">
			<div class="bx-authform-label-container">
				<span class="bx-authform-starrequired">*</span><?=GetMessage("CAPTCHA_REGF_PROMT")?>
			</div>
			<div class="bx-captcha"><img src="/ajax/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="240" height="72" alt="Код с картинки" /></div>
			<div class="bx-authform-input-container">
				<input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
			</div>
		</div>

<?php endif?>
		<div class="bx-authform-formgroup-container">
			<?php include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/auth_consent.php'; ?>
		</div>
		<div class="bx-authform-formgroup-container">
			<input type="submit" class="btn btn-primary" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" />
		</div>

		<hr class="bxe-light">

		<div class="bx-authform-description-container">
			<?= $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
		</div>

		<div class="bx-authform-description-container">
			<span class="bx-authform-starrequired">*</span><?=GetMessage("AUTH_REQ")?>
		</div>

		<p class="auth-back">У вас уже есть аккаунт? <a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow">Войти</a></p>

	</form>

<script>
document.bform.USER_NAME.focus();
(function () {
    var button = document.getElementById('reg-pass-gen');
    var password = document.getElementById('reg-password');
    var confirm = document.getElementById('reg-password-confirm');
    if (!button || !password || !confirm) return;
    var alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
    function pick(source) {
        var list = new Uint32Array(1);
        crypto.getRandomValues(list);
        return source[list[0] % source.length];
    }
    button.addEventListener('click', function () {
        var value = pick('ABCDEFGHJKLMNPQRSTUVWXYZ') + pick('abcdefghijkmnpqrstuvwxyz') + pick('23456789');
        while (value.length < 10) value += pick(alphabet);
        password.type = 'text';
        confirm.type = 'text';
        password.value = value;
        confirm.value = value;
        password.focus();
    });
})();
</script>

<?php endif?>

</noindex>
</div>
</div>