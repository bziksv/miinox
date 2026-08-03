<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;

/*patchvalidationmutatormark1*/

class CGrain_CustomSettingsOptions 
{
	public static function IsLinksInstalled() 
	{
		static $bInstalled;
		
		if($bInstalled===true) {
			return true;
		} elseif($bInstalled===false) {
			return false;
		} else {
			$bInstalled=IsModuleInstalled("grain.links");
			return $bInstalled;
		}
	}

	public static function SortArrayByKey($array,$key,$order,$maintain_index=true,$method="FLOAT") 
	{
		// array stable-sort function

		if (!is_array($array) || (is_array($array) && count($array)<2)) return $array;
	
		switch($method) 
		{
			case "STRING":
				$cmpf = function($a,$b) use ($key,$order) {
					if($a[$key]==$b[$key]) 
						return 0;
					if(strtolower($order)=="desc")
						return !strcasecmp($a[$key],$b[$key]);
					else
						return strcasecmp($a[$key],$b[$key]);					
				};			
				break;
			case "INT":
				$cmpf = function($a,$b) use ($key,$order) {
					if(floatval($a[$key])==floatval($b[$key]))
						return 0;
					if(strtolower($order)=="desc")
						return floatval($a[$key])<floatval($b[$key]);
					else
						return floatval($a[$key])>floatval($b[$key]);
				};
				break;
			default: // default is FLOAT
				$cmpf = function($a,$b) use ($key,$order) {
					if(intval($a[$key])==intval($b[$key]))
						return 0;
					if(strtolower($order)=="desc")
						return intval($a[$key])<intval($b[$key]);
					else
						return intval($a[$key])>intval($b[$key]);
				};
				break;
		}
	
		if(!$maintain_index) 
		{
			$half = floor(count($array)/2); 
			$array1 = array_slice($array, 0, $half); 
			$array2 = array_slice($array, $half); 
			$array1 = static::SortArrayByKey($array1,$key,$order,$maintain_index,$method); 
			$array2 = static::SortArrayByKey($array2,$key,$order,$maintain_index,$method); 
			if ($cmpf($array1[count($array1)-1], $array2[0]) < 1) { 
			    $array = array_merge($array1, $array2); 
			    return $array; 
			} 
			$array=array(); $i1=0; $i2=0; 
			while ($i1 < count($array1) && $i2 < count($array2)) { 
			    if ($cmpf($array1[$i1], $array2[$i2]) < 1) $array[] = $array1[$i1++]; 
			    else $array[] = $array2[$i2++]; 
			} 
			while ($i1 < count($array1)) $array[] = $array1[$i1++]; 
			while ($i2 < count($array2)) $array[] = $array2[$i2++]; 
			return $array;
		} 
		else 
		{
			$half = floor(count($array)/2); 
			$array1 = array_slice($array, 0, $half, true); 
			$array2 = array_slice($array, $half, (count($array)-$half), true); 
			$array1 = static::SortArrayByKey($array1,$key,$order,$maintain_index,$method); 
			$array2 = static::SortArrayByKey($array2,$key,$order,$maintain_index,$method);
			$keys1 = array_keys($array1);
			$keys2 = array_keys($array2);
			if ($cmpf($array1[$keys1[count($keys1)-1]], $array2[$keys2[0]]) < 1) { 
			    $array = array_merge($array1, $array2); 
			    return $array; 
			} 
			$array=array(); $i1=0; $i2=0; 
			while ($i1 < count($keys1) && $i2 < count($keys2)) { 
			    if ($cmpf($array1[$keys1[$i1]], $array2[$keys2[$i2]]) < 1) { 
			        $array[$keys1[$i1]] = $array1[$keys1[$i1]]; $i1++;
			    } else { 
			        $array[$keys2[$i2]] = $array2[$keys2[$i2]]; $i2++;
			    } 
			} 
			while ($i1 < count($keys1)) {
				$array[$keys1[$i1]] = $array1[$keys1[$i1]]; $i1++;
			}
			while ($i2 < count($keys2)) {
				$array[$keys2[$i2]] = $array2[$keys2[$i2]]; $i2++;
			}
			return $array;
		}
	} 

	public static function ShowTab($gks_tab_id,$tab=Array(),$set_variables=true) 
	{
		global $APPLICATION;
		global $arLang;
	
		ob_start();
		?>
		<div class="gcustomsettings-settings-tab" id="gks_tab_<?=$gks_tab_id?>">
		
			<table class="gcustomsettings-settings-tab-headers" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_NAME")?></td>
						<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_HEADER")?></td>
						<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_SORT")?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<table cellspacing="0" cellpadding="0">
								<?php foreach($arLang as $lang_id => $lang):?>
									<tr>
										<td><?=$lang_id?></td>
										<td><input type="text" name="TABS[<?=$gks_tab_id?>][LANG][<?=$lang_id?>][NAME]" value="<?=isset($tab["LANG"][$lang_id]["NAME"])?htmlspecialchars($tab["LANG"][$lang_id]["NAME"]):""?>" /></td>
									</tr>
								<?php endforeach;?>
							</table>
						</td>
						<td>
							<table cellspacing="0" cellpadding="0">
								<?php foreach($arLang as $lang_id => $lang):?>
									<tr>
										<td><?=$lang_id?></td>
										<td><input type="text" name="TABS[<?=$gks_tab_id?>][LANG][<?=$lang_id?>][TITLE]" value="<?=isset($tab["LANG"][$lang_id]["TITLE"])?htmlspecialchars($tab["LANG"][$lang_id]["TITLE"]):""?>" /></td>
									</tr>
								<?php endforeach;?>
							</table>
						</td>
						<td>
							<input type="text" size="6" name="TABS[<?=$gks_tab_id?>][SORT]" value="<?=isset($tab["SORT"])?htmlspecialchars($tab["SORT"]):"500"?>" />
						</td>
					</tr>
				</tbody>
			</table>
		
			<?php if($set_variables):?>
			<script type="text/javascript">
			gks_options[<?=$gks_tab_id?>]=0;
			gks_selectvalues[<?=$gks_tab_id?>]=[];		
			</script>
			<?php endif?>
		
			<div class="gcustomsettings-settings-tab-options" id="gks_tab_options_<?=$gks_tab_id?>">
		
				<?php
					$option_count=1;
					if(isset($tab["FIELDS"])) foreach($tab["FIELDS"] as $option_data) {
						echo self::ShowOption($gks_tab_id,$option_count,$option_data,true);
						$option_count++;
					}
				?>
		
			</div>
			
			<div class="gcustomsettings-settings-option-add">
				<a href="#" onclick="gksAddOption('<?=$gks_tab_id?>'); return false;"><img src="/bitrix/images/grain.customsettings/gcustomsettings_options_option_icon_add.gif" width="24" height="24" border="0" />&nbsp;&nbsp;<span><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_ADD_OPTION")?></span></a>
			</div>
		
			<a class="gcustomsettings-settings-tab-remove" href="#" onclick="if(confirm('<?php echo AddSlashes(Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_REMOVE_TAB_WARNING"))?>')) gksRemoveTab('<?=$gks_tab_id?>'); return false"><img src="/bitrix/images/grain.customsettings/gcustomsettings_options_tab_icon_remove.gif" width="30" height="15" border="0" />&nbsp;&nbsp;<span><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_REMOVE_TAB")?></span></a>
		
		</div>
		<?php
		$s .= ob_get_contents();
		ob_end_clean();
	
		return $s;
	}
	

	public static function ShowOption($gks_tab_id,$gks_option_id,$option=Array(),$set_variables=true) 
	{
		global $APPLICATION;
		global $arLang;
	
		ob_start();	
		?>
		<div class="gcustomsettings-settings-option" id="gks_option_<?=$gks_tab_id?>_<?=$gks_option_id?>">
		
			<?php if($set_variables):?>
			<script type="text/javascript">
			gks_options[<?=$gks_tab_id?>]++;	
			</script>
			<?php endif?>
		
			<table cellspacing="0" cellpadding="0" class="gcustomsettings-settings-option-table">
				<thead>
					<tr>
						<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_ID")?></td>		
						<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_NAME")?></td>
						<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TOOLTIP")?></td>
						<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TYPE")?></td>
						<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_CUSTOM")?></td>
						<td>&nbsp;</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][NAME]" value="<?=isset($option["NAME"])?$option["NAME"]:""?>" /><br />&nbsp;<span class="gcustomsettings-settings-sort-caption"><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_SORT")?></span><input class="gcustomsettings-settings-sort-field" type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][SORT]" value="<?=isset($option["SORT"])?htmlspecialchars($option["SORT"]):"500"?>" /></td>
						<td>
							<table cellspacing="0" cellpadding="0">
								<?php foreach($arLang as $lang_id => $lang):?>
									<tr>
										<td><?=$lang_id?></td>
										<td><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][LANG][<?=$lang_id?>][NAME]" value="<?=isset($option["LANG"][$lang_id]["NAME"])?htmlspecialchars($option["LANG"][$lang_id]["NAME"]):""?>" /></td>
									</tr>
								<?php endforeach;?>
							</table>
						</td>
						<td>
							<table cellspacing="0" cellpadding="0">
								<?php foreach($arLang as $lang_id => $lang):?>
									<tr>
										<td><?=$lang_id?></td>
										<td><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][LANG][<?=$lang_id?>][TOOLTIP]" value="<?=isset($option["LANG"][$lang_id]["TOOLTIP"])?htmlspecialchars($option["LANG"][$lang_id]["TOOLTIP"]):""?>" /></td>
									</tr>
								<?php endforeach;?>
							</table>
						</td>
						<td>
							<select name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][TYPE]" onChange="gksOptionChangeType('<?=$gks_tab_id?>','<?=$gks_option_id?>',this);">
								<option value="text"<?php if($option["TYPE"]=="text"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TYPE_TEXT")?></option>
								<option value="textarea"<?php if($option["TYPE"]=="textarea"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TYPE_TEXTAREA")?></option>
								<option value="checkbox"<?php if($option["TYPE"]=="checkbox"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TYPE_CHECKBOX")?></option>
								<option value="select"<?php if($option["TYPE"]=="select"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TYPE_SELECT")?></option>
								<option value="date"<?php if($option["TYPE"]=="date"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TYPE_DATE")?></option>
								<option value="link"<?php if($option["TYPE"]=="link"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TYPE_LINK")?></option>
							</select>
						</td>
						<td width="99%">
							<div class="gcustomsettings-settings-option-custom" id="gks_tab_option_custom_<?=$gks_tab_id?>_<?=$gks_option_id?>">
								<?=self::ShowOptionCustom($gks_tab_id,$gks_option_id,$option)?>
							</div>
						</td>
						<td>
				
							<a class="gcustomsettings-settings-option-remove" href="#" onclick="gksRemoveOption('<?=$gks_tab_id?>','<?=$gks_option_id?>'); return false" title="<?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_REMOVE_OPTION")?>"><img src="/bitrix/images/grain.customsettings/gcustomsettings_options_option_icon_remove.gif" width="24" height="24" border="0" /></a>
				
						</td>
					</tr>
				</tbody>
			</table>
		
		</div>
		
		<?php
		$s .= ob_get_contents();
		ob_end_clean();
	
		return $s;
	}
	
	public static function ShowOptionCustom($gks_tab_id,$gks_option_id,$option,$set_variables=true) 
	{
	
		global $APPLICATION;
		global $arLang;
	
		ob_start();
	
		switch($option["TYPE"]):
	
		case "text":
		?>
			<table cellspacing="0" cellpadding="0"><tr>
				<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_CUSTOM_DEFAULT_VALUE")?></td>
				<td><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][DEFAULT_VALUE]" value="<?=isset($option["DEFAULT_VALUE"])?htmlspecialchars($option["DEFAULT_VALUE"]):""?>" /></td>
			</tr></table>
			<table cellspacing="0" cellpadding="0"><tr>
				<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TYPE_TEXT_SIZE")?></td>
				<td><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][SIZE]" size="4" value="<?=isset($option["SIZE"])?htmlspecialchars($option["SIZE"]):""?>" /></td>
			</tr></table>
		<?php
		break;
	
		case "textarea":
		?>
			<table cellspacing="0" cellpadding="0"><tr>
				<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_CUSTOM_DEFAULT_VALUE")?></td>
				<td><textarea name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][DEFAULT_VALUE]"><?=isset($option["DEFAULT_VALUE"])?htmlspecialchars($option["DEFAULT_VALUE"]):""?></textarea></td>
			</tr></table>
			<table cellspacing="0" cellpadding="0"><tr>
				<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_TYPE_TEXT_SIZE")?></td>
				<td><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][COLS]" size="4" value="<?=isset($option["COLS"])?htmlspecialchars($option["COLS"]):""?>" /></td>
				<td>x</td>
				<td><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][ROWS]" size="4" value="<?=isset($option["ROWS"])?htmlspecialchars($option["ROWS"]):""?>" /></td>
			</tr></table>
		<?php
		break;
	
		case "checkbox":
		?>
			<table cellspacing="0" cellpadding="0"><tr>
				<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_CUSTOM_DEFAULT_VALUE")?></td>
				<td><input type="checkbox" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][DEFAULT_VALUE]" value="Y"<?php if(isset($option["DEFAULT_VALUE"]) && $option["DEFAULT_VALUE"]=="Y"):?> checked="checked"<?php endif?> /></td>
			</tr></table>
		<?php
		break;
	
		case "select":
		?>
	
			<?php if($set_variables):?>
			<script type="text/javascript">
	
				gks_selectvalues[<?=$gks_tab_id?>][<?=$gks_option_id?>]=0;
	
			</script>
			<?php endif?>
	
			<table cellspacing="0" cellpadding="0"><tr>
				<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_CUSTOM_DEFAULT_VALUE")?></td>
				<td>
					<select name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][DEFAULT_VALUE]">
						<?php if(isset($option["VALUES"])) foreach($option["VALUES"] as $value):?>
							<option value="<?=htmlspecialchars($value["VALUE"])?>"<?php if($option["DEFAULT_VALUE"]==$value["VALUE"]):?> selected="selected"<?php endif?>><?=$value["LANG"][LANGUAGE_ID]?></option>
						<?php endforeach?>
					</select>
				</td>
			</tr></table>
			<table class="gcustomsettings-settings-selectvalue-table">
				<thead>
					<tr>
						<td class="gcustomsettings-settings-selectvalue-col-value"><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_SELECTVALUE_TD_VALUE")?></td>
						<td class="gcustomsettings-settings-selectvalue-col-name"><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_SELECTVALUE_TD_NAME")?></td>
						<td class="gcustomsettings-settings-selectvalue-col-remove">&nbsp;</td>
					</tr>
				</thead>
			</table>
			
			<div id="gks_option_selectvalues_<?=$gks_tab_id?>_<?=$gks_option_id?>">
			<?php $gks_selectvalue_id=1;foreach($option["VALUES"] as $value):?>
				<div id="gks_selectvalue_<?=$gks_tab_id?>_<?=$gks_option_id?>_<?=$gks_selectvalue_id?>">
					<?=self::ShowOptionSelectvalue($gks_tab_id,$gks_option_id,$gks_selectvalue_id,$value,true)?>
				</div>
			<?php $gks_selectvalue_id++;endforeach?>
			</div>
			<div class="gcustomsettings-settings-selectvalue-add">
				<a href="#" onclick="gksAddSelectValue('<?=$gks_tab_id?>','<?=$gks_option_id?>'); return false;"><img src="/bitrix/images/grain.customsettings/gcustomsettings_options_option_icon_add.gif" width="16" height="16" border="0" />&nbsp;&nbsp;<span><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_SELECTVALUE_ADD")?></span></a>
			</div>
			
		<?php
		break;
	
		case "date":
		?>
			<table cellspacing="0" cellpadding="0"><tr>
				<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_TAB_OPTION_CUSTOM_DEFAULT_VALUE")?></td>
				<td><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][DEFAULT_VALUE]" value="<?=isset($option["DEFAULT_VALUE"])?htmlspecialchars($option["DEFAULT_VALUE"]):""?>" /></td>
				<td><?=Calendar(htmlspecialchars("TABS[".$gks_tab_id."][FIELDS][".$gks_option_id."][DEFAULT_VALUE]"),"gcs_settings_form")?></td>
			</tr></table>
		<?php
		break;

		case "link":
		?>
			<?php if(self::IsLinksInstalled()):?>
				<input type="button" style="margin: 5px 0" onclick="gksShowLinksDataSourcePopup('TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][LINK]','gks_column_linkparams_<?=$gks_tab_id?>_<?=$gks_option_id?>'); return false;" value="<?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_LINK_SET_UP_DATA_SOURCE")?>" />
				<div style="margin: 5px 0">
					<?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_LINK_INTERFACE_TYPE")?>:<br />
					<select name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][INTERFACE]">
						<option value="ajax"<?php if(isset($option["INTERFACE"]) && $option["INTERFACE"]=="ajax"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_LINK_INTERFACE_AJAX")?></option>
						<option value="select"<?php if(isset($option["INTERFACE"]) && $option["INTERFACE"]=="select"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_LINK_INTERFACE_SELECT")?></option>
						<option value="selectsearch"<?php if(isset($option["INTERFACE"]) && $option["INTERFACE"]=="selectsearch"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_LINK_INTERFACE_SELECTSEARCH")?></option>
						<option value="search"<?php if(isset($option["INTERFACE"]) && $option["INTERFACE"]=="search"):?> selected="selected"<?php endif?>><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_LINK_INTERFACE_SEARCH")?></option>
					</select>
				</div>
				<label><table cellspacing="0" cellpadding="0"><tr>
					<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_LINK_INTERFACE_MULTIPLE")?></td>
					<td><input type="checkbox" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][MULTIPLE]" value="Y"<?php if(isset($option["MULTIPLE"]) && $option["MULTIPLE"]=="Y"):?> checked="checked"<?php endif?> /></td>
				</tr></table></label>
				<label><table cellspacing="0" cellpadding="0"><tr>
					<td><?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_LINK_INTERFACE_SHOW_URL")?></td>
					<td><input type="checkbox" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][SHOW_URL]" value="Y"<?php if(isset($option["SHOW_URL"]) && $option["SHOW_URL"]=="Y"):?> checked="checked"<?php endif?> /></td>
				</tr></table></label>
				<div id="gks_column_linkparams_<?=$gks_tab_id?>_<?=$gks_option_id?>">
					<?php if(isset($option["LINK"]) && is_array($option["LINK"])):foreach($option["LINK"] as $param_name=>$param_value):?>
						<?php if(is_array($param_value)):?>
							<?php foreach($param_value as $k=>$v):?>
								<input type="hidden" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][LINK][<?=$param_name?>][<?=$k?>]" value="<?=htmlspecialchars($v)?>" />
							<?php endforeach?>
						<?php else:?>
							<input type="hidden" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][LINK][<?=$param_name?>]" value="<?=htmlspecialchars($param_value)?>" />
						<?php endif?>
					<?php endforeach;endif?>
				</div>
			<?php else:?>
				<?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_LINK_NOT_INSTALLED")?>
			<?php endif?>
		<?php
		break;
	
		default:	
		
			echo "Wrong type";
	
		endswitch;
	
		$s .= ob_get_contents();
		ob_end_clean();
	
		return $s;
	}


	public static function ShowOptionSelectvalue($gks_tab_id,$gks_option_id,$gks_selectvalue_id,$value=Array(),$set_variables=true) 
	{

		global $APPLICATION;
		global $arLang;
	
		ob_start();
	
		?>
	
		<?php if($set_variables):?>
		<script type="text/javascript">
	
			gks_selectvalues[<?=$gks_tab_id?>][<?=$gks_option_id?>]++;
	
		</script>
		<?php endif?>
	
		<table cellspacing="0" cellpadding="0" class="gcustomsettings-settings-selectvalue-table">
	    	<tr>
	    		<td class="gcustomsettings-settings-selectvalue-col-value"><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][VALUES][<?=$gks_selectvalue_id?>][VALUE]" value="<?=isset($value["VALUE"])?htmlspecialchars($value["VALUE"]):""?>" /></td>
	    		<td class="gcustomsettings-settings-selectvalue-col-name">
	    			<table cellspacing="0" cellpadding="0">
	    				<?php foreach($arLang as $lang_id => $lang):?>
	    					<tr>
	    						<td class="gcustomsettings-settings-selectvalue-col-name-lang-id"><?=$lang_id?></td>
	    						<td class="gcustomsettings-settings-selectvalue-col-name-name"><input type="text" name="TABS[<?=$gks_tab_id?>][FIELDS][<?=$gks_option_id?>][VALUES][<?=$gks_selectvalue_id?>][LANG][<?=$lang_id?>]" value="<?=isset($value["LANG"][$lang_id])?htmlspecialchars($value["LANG"][$lang_id]):""?>" /></td>
	    					</tr>
	    				<?php endforeach;?>
	    			</table>
	    		</td>
	    		<td class="gcustomsettings-settings-selectvalue-col-remove"><a href="#" onclick="gksRemoveSelectValue('<?=$gks_tab_id?>','<?=$gks_option_id?>','<?=$gks_selectvalue_id?>'); return false;" title="<?=Loc::getMessage("GRAIN_CUSTOMSETTINGS_OPTIONS_SETTINGS_SELECTVALUE_REMOVE")?>"><img src="/bitrix/images/grain.customsettings/gcustomsettings_options_option_icon_remove.gif" width="16" height="16" border="0" /></a></td>
	    	</tr>
	    </table>
	
		<?php
		$s .= ob_get_contents();
		ob_end_clean();
	
		return $s;
	}

}

/*patchvalidationmutatormark2*/

