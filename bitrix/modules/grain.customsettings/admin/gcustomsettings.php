<?php
	
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

Loader::includeModule('grain.customsettings');

$arCustomPage = Array();
$arCustomSettings = Array();

$handle = fopen($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/admin/settings_data.php", "r");
$settings_data=fread($handle, filesize($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/admin/settings_data.php"));
fclose($handle);

ob_start();
$settings_data_error = eval("?>".$settings_data."<?php ")===false;
$err = ob_get_contents();
ob_end_clean();

$settings_data_empty = (is_array($arCustomSettings) && count($arCustomSettings)<=0) || !is_array($arCustomSettings);

//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/admin/settings_data.php");

Loc::loadMessages(__FILE__);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/prolog.php");

$GKS_RIGHT = $APPLICATION->GetGroupRight("grain.customsettings");

if ($GKS_RIGHT == "D")
  $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$aTabs = Array();

foreach($arCustomSettings as $tab_id => $arTab) 
{
	$aTabs[] = Array(
		"DIV" => "edit".$tab_id,
		"TAB" => $arTab["LANG"][LANGUAGE_ID]["NAME"],
		"ICON"=>"main_user_edit",
		"TITLE"=>$arTab["LANG"][LANGUAGE_ID]["TITLE"]
	);
}

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if ($REQUEST_METHOD=="GET" && isset($RestoreDefaults) && strlen($RestoreDefaults)>0 && $GKS_RIGHT>="S" && check_bitrix_sessid())
{
	Option::delete("grain.customsettings");
	LocalRedirect("/bitrix/admin/gcustomsettings.php?lang=".LANG);
}

if(
	$REQUEST_METHOD == "POST"
	&& ($save!="" || $apply!="")
	&& $GKS_RIGHT>="S"
	&& check_bitrix_sessid()
)
{
	foreach($arCustomSettings as $tab_id => $arTab) 
	{
		foreach($arTab["FIELDS"] as $arField) 
		{
			$val = ${$arField["NAME"]};
		
			if(is_array($val))
			{
				$tmp = $val;
				$val = '';
				foreach($tmp as $v)
				{
					if((strlen($val)+1+strlen($v))<=255)
						$val .= ($val?',':'').$v;
				}
			}
		
			Option::set(
				"grain.customsettings", 
				$arField["NAME"], 
				$val
			);
		}
	}

	LocalRedirect("/bitrix/admin/gcustomsettings.php?lang=".LANG."&mess=ok&".$tabControl->ActiveTabParam());

}


$APPLICATION->SetTitle($arCustomPage["LANG"][LANGUAGE_ID]["PAGE_TITLE"]?$arCustomPage["LANG"][LANGUAGE_ID]["PAGE_TITLE"]:GetMessage("GRAIN_CUSTOMSETTINGS_ADMIN_GCUSTOMSETTINGS_TITLE"));

// split data prepare and out
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if($_REQUEST["mess"] == "ok")
  CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("GRAIN_CUSTOMSETTINGS_ADMIN_GCUSTOMSETTINGS_DATA_SAVED"), "TYPE"=>"OK"));

if($settings_data_error)
  CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("GRAIN_CUSTOMSETTINGS_ADMIN_GCUSTOMSETTINGS_DATA_FILE_ERROR"), "TYPE"=>"ERROR"));
elseif($settings_data_empty && $GKS_RIGHT=="W") {

?>
<?php echo BeginNote();?>
	<?=GetMessage("GRAIN_CUSTOMSETTINGS_ADMIN_GCUSTOMSETTINGS_NO_SETTINGS_NOTE",Array("#SETTINGS_URL#"=>"/bitrix/admin/settings.php?lang=".LANGUAGE_ID."&mid=grain.customsettings"."&back_url_settings=".urlencode($APPLICATION->GetCurPageParam())))?>
<?php echo EndNote();?>
<?php

}


if (!$settings_data_error && !$settings_data_empty):

?>
<form method="POST" Action="<?php echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="post_form">
<?php // check session identifier ?>
<?php echo bitrix_sessid_post();?>
<?php
// Show tab headers  
$tabControl->Begin();
?>
<?php

foreach($arCustomSettings as $tab_id => $arTab):
	$tabControl->BeginNextTab();

	foreach($arTab["FIELDS"] as $arField):
		$val = Option::get("grain.customsettings", $arField["NAME"]);
	?>
		<tr>
			<td valign="top" width="50%"><?php if($arField["TYPE"]=="checkbox")
							echo "<label for=\"".htmlspecialchars($arField["NAME"])."_cchbc\">".$arField["LANG"][LANGUAGE_ID]["NAME"]."</label>";
						else
							echo $arField["LANG"][LANGUAGE_ID]["NAME"];?></td>
			<td valign="top" width="50%">
					<?php if($arField["TYPE"]=="checkbox"):?>
						<input type="hidden" name="<?php echo htmlspecialchars($arField["NAME"])?>" value="N">
						<input type="checkbox" name="<?php echo htmlspecialchars($arField["NAME"])?>" id="<?php echo htmlspecialchars($arField["NAME"])?>_cchbc" value="Y"<?php if($val=="Y")echo" checked";?>>
					<?php elseif($arField["TYPE"]=="text"):?>
						<input type="text" maxlength="255" value="<?php echo htmlspecialchars($val)?>" <?php if($arField["SIZE"]):?>size="<?=$arField["SIZE"]?>" <?php endif?>name="<?php echo htmlspecialchars($arField["NAME"])?>">
					<?php elseif($arField["TYPE"]=="date"):?>
						<input type="text" maxlength="255" value="<?php echo htmlspecialchars($val)?>" name="<?php echo htmlspecialchars($arField["NAME"])?>"> <?=Calendar(htmlspecialchars($arField["NAME"]),"post_form")?>
					<?php elseif($arField["TYPE"]=="textarea"):?>
						<textarea name="<?php echo htmlspecialchars($arField["NAME"])?>"<?php if($arField["COLS"]):?> cols="<?=$arField["COLS"]?>"<?php endif?><?php if($arField["ROWS"]):?> rows="<?=$arField["ROWS"]?>"<?php endif?>><?php echo htmlspecialchars($val)?></textarea>
					<?php elseif($arField["TYPE"]=="select"):?>
						<select  name="<?php echo htmlspecialchars($arField["NAME"])?>">
							<?php foreach($arField["VALUES"] as $v):?>
								<option value="<?=$v["VALUE"]?>" <?php if($val==$v["VALUE"]) echo 'selected';?>><?=$v["LANG"][LANGUAGE_ID]?></option>
							<?php endforeach?>
						</select>
					<?php elseif($arField["TYPE"]=="link"):?>
						<?php
						$arParameters = $arField["LINK"];
					
						$arParameters["INPUT_NAME"] = $arField["NAME"];
						$arParameters["USE_SEARCH"] = in_array($arField["INTERFACE"],Array("search","selectsearch"))?"Y":"N";
						$arParameters["USE_SEARCH_COUNT"] = "";
						$arParameters["EMPTY_SHOW_ALL"] = in_array($arField["INTERFACE"],Array("select","selectsearch"))?"Y":"N";
						$arParameters["NAME_TRUNCATE_LEN"] = "";
						$arParameters["USE_AJAX"] = $arField["INTERFACE"]=="ajax"?"Y":"N";
						$arParameters["VALUE"] = $arField["MULTIPLE"]=="Y"?explode(',',$val):$val;
						$arParameters["MULTIPLE"] = $arField["MULTIPLE"]=="Y"?"Y":"N";
						$arParameters["ADMIN_SECTION"] = "Y";
						$arParameters["LEAVE_EMPTY_INPUTS"] = "N";
						$arParameters["USE_VALUE_ID"] = "N";
						$arParameters["SHOW_URL"] = $arField["SHOW_URL"]=="Y"?"Y":"N";
								
						$GLOBALS["APPLICATION"]->IncludeComponent(
							"grain:links.edit",
							"",
							$arParameters,
							null,
							array('HIDE_ICONS' => 'Y')
						);
						?>
					<?php endif?>
					
					<?php if($arField["LANG"][LANGUAGE_ID]["TOOLTIP"]):?>
						<?php echo BeginNote();?>
							<?php echo $arField["LANG"][LANGUAGE_ID]["TOOLTIP"];?>
						<?php echo EndNote();?>
					<?php endif?>
					
			</td>
		</tr>
	<?php endforeach;
endforeach;?>

<?php
// show buttons 
$tabControl->Buttons();
?>
<script language="JavaScript">
function RestoreDefaults()
{
	if (confirm('<?php echo AddSlashes(GetMessage("GRAIN_CUSTOMSETTINGS_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
		window.location = "<?php echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?php echo LANG?>&<?php echo bitrix_sessid_get()?>";
}
</script>

<input type="submit" <?php if ($GKS_RIGHT<"S") echo "disabled" ?> name="save" value="<?php echo GetMessage("MAIN_SAVE")?>">
&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" <?php if ($GKS_RIGHT<"S"):?>disabled<?php else:?>onClick="RestoreDefaults();"<?php endif?> value="<?php echo GetMessage("GRAIN_CUSTOMSETTINGS_RESTORE_DEFAULTS")?>" />
<input type="hidden" name="lang" value="<?=LANG?>">
<?php
// end tab interface
$tabControl->End();

if($GKS_RIGHT=="W") 
{
	echo BeginNote();
	?>
		<?=GetMessage("GRAIN_CUSTOMSETTINGS_ADMIN_GCUSTOMSETTINGS_MODULE_SETTINGS_NOTE",Array("#SETTINGS_URL#"=>"/bitrix/admin/settings.php?lang=".LANGUAGE_ID."&mid=grain.customsettings"."&back_url_settings=".urlencode($APPLICATION->GetCurPageParam())))?>
	<?php
	echo EndNote();
}

endif;
 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
