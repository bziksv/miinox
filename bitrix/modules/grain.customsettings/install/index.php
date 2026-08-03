<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity\Base;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

Class grain_customsettings extends CModule
{
	var $MODULE_ID = "grain.customsettings";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $PARTNER_NAME;
	var $PARTNER_URI;
	var $MODULE_GROUP_RIGHTS = "Y";

	function __construct() 
	{
		$arModuleVersion = array();
		include(__DIR__."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("GCUSTOMSETTINGS_MODULE_NAME"); 
		$this->MODULE_DESCRIPTION = Loc::getMessage("GCUSTOMSETTINGS_MODULE_DESC"); 
		$this->PARTNER_URI = GetMessage("GCUSTOMSETTINGS_PARTNER_URL"); // old GetMessage for marketplace compatibility
		$this->PARTNER_NAME = GetMessage("GCUSTOMSETTINGS_PARTNER_NAME"); // old GetMessage for marketplace compatibility
	}

	function DoInstall() 
	{
		/*patchinstallmutatormark1*/

		$this->InstallFiles();

		\Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);
		
		/*patchinstallmutatormark2*/
	}

	function DoUninstall()
	{
		global $APPLICATION;

		\Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

		$this->UnInstallFiles();
			
		$GLOBALS["errors"] = $this->errors;

		Option::delete($this->MODULE_ID);

		$APPLICATION->IncludeAdminFile(Loc::getMessage("GCUSTOMSETTINGS_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/install/unstep2.php");

	}


	function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/install/images",  $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/grain.customsettings", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/install/themes", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes", true, true);

		// copy settings files, because of marketplace no converting charset in files not in lang directory 
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/lang/ru/default_settings", $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/admin", true, true);

		return true;
	}	

	function UnInstallFiles()
	{

		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");

		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/install/themes/.default/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default");//css
		DeleteDirFilesEx("/bitrix/themes/.default/icons/grain.customsettings/");//icons
		DeleteDirFilesEx("/bitrix/themes/.default/start_menu/grain.customsettings/");//start menu icons
		DeleteDirFilesEx("/bitrix/images/grain.customsettings/");//images

		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/lang/ru/default_settings/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/grain.customsettings/admin"); // default settings files

		return true;
	}


	function GetModuleRightList()
	{
		$arr = Array(
			"reference_id" => array("D","R","S","W"),
			"reference" => array(
				"[D] ".Loc::getMessage("GCUSTOMSETTINGS_RIGHTS_D"),
				"[R] ".Loc::getMessage("GCUSTOMSETTINGS_RIGHTS_R"),
				"[S] ".Loc::getMessage("GCUSTOMSETTINGS_RIGHTS_S"),
				"[W] ".Loc::getMessage("GCUSTOMSETTINGS_RIGHTS_W"),
			)
		);
		return $arr;
	}
} 

?>