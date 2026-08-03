<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
/**
* @var array $arParams
* @var array $arResult
* @var CMain $APPLICATION
* @var CBitrixComponent $component
* @var CBitrixComponentTemplate $this
*/

$position = "tooltip-bottom";

if ($arParams["POSITOPN"]) {
	$position = $arParams["POSITOPN"];
}
?>

<? if ($arParams["SHOW"] === "Y"): ?>
	<div class="tooltip"> 
	  <button class="ads_btn" title="">Реклама</button>
	  <span class="tooltiptext <?=$position?>"><?=$arParams["~DESCRIPTION"]?></span>
	</div>
<? endif; ?>