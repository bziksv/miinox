<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arViewModeList = array('LIST', 'LINE', 'TEXT', 'TILE');

$arDefaultParams = array(
	'VIEW_MODE' => 'LIST',
	'SHOW_PARENT_NAME' => 'Y',
	'HIDE_SECTION_NAME' => 'N'
);

$arParams = array_merge($arDefaultParams, $arParams);

if (!in_array($arParams['VIEW_MODE'], $arViewModeList))
	$arParams['VIEW_MODE'] = 'LIST';
if ('N' != $arParams['SHOW_PARENT_NAME'])
	$arParams['SHOW_PARENT_NAME'] = 'Y';
if ('Y' != $arParams['HIDE_SECTION_NAME'])
	$arParams['HIDE_SECTION_NAME'] = 'N';

$arResult['VIEW_MODE_LIST'] = $arViewModeList;

if (0 < $arResult['SECTIONS_COUNT'])
{
	$boolPicture = false;
	$boolDescr = false;
	$arSelect = array('ID');
	$arMap = array();
	if ('LINE' == $arParams['VIEW_MODE'] || 'TILE' == $arParams['VIEW_MODE'])
	{
		reset($arResult['SECTIONS']);
		$arCurrent = current($arResult['SECTIONS']);
		if (!isset($arCurrent['PICTURE']))
		{
			$boolPicture = true;
			$arSelect[] = 'PICTURE';
		}
		if ('LINE' == $arParams['VIEW_MODE'] && !array_key_exists('DESCRIPTION', $arCurrent))
		{
			$boolDescr = true;
			$arSelect[] = 'DESCRIPTION';
			$arSelect[] = 'DESCRIPTION_TYPE';
		}
	}
	if ($boolPicture || $boolDescr)
	{
		foreach ($arResult['SECTIONS'] as $key => $arSection)
		{
			$arMap[$arSection['ID']] = $key;
		}
		$rsSections = CIBlockSection::GetList(array(), array('ID' => array_keys($arMap)), false, $arSelect);
		while ($arSection = $rsSections->GetNext())
		{
			if (!isset($arMap[$arSection['ID']]))
				continue;
			$key = $arMap[$arSection['ID']];
			if ($boolPicture)
			{
				$arSection['PICTURE'] = intval($arSection['PICTURE']);
				$arSection['PICTURE'] = (0 < $arSection['PICTURE'] ? CFile::GetFileArray($arSection['PICTURE']) : false);
				$arResult['SECTIONS'][$key]['PICTURE'] = $arSection['PICTURE'];
				$arResult['SECTIONS'][$key]['~PICTURE'] = $arSection['~PICTURE'];
			}
			if ($boolDescr)
			{
				$arResult['SECTIONS'][$key]['DESCRIPTION'] = $arSection['DESCRIPTION'];
				$arResult['SECTIONS'][$key]['~DESCRIPTION'] = $arSection['~DESCRIPTION'];
				$arResult['SECTIONS'][$key]['DESCRIPTION_TYPE'] = $arSection['DESCRIPTION_TYPE'];
				$arResult['SECTIONS'][$key]['~DESCRIPTION_TYPE'] = $arSection['~DESCRIPTION_TYPE'];
			}
		}
	}
}

/**
 * UF_ICON с 1С часто пустой — подставляем классы спрайта по CODE/NAME,
 * как на metplus-vrn (иначе .mi-category-icon без суффикса скрыт в CSS).
 */
$iconByCode = array(
	'zakaznye_nerzh' => 'mi-category-icon_twenty',
	'zapornaya_armatura_nerzhaveyushchaya' => 'mi-category-icon_first',
	'komplektuyushchie_dlya_peril_iz_nerzhaveyushchey_stali' => 'mi-category-icon_fifteen',
	'listy_nerzhaveyushchie' => 'mi-category-icon_seven',
	'metizy_nerzhaveyushchie' => 'mi-category-icon_eleven',
	'rezbovye_soedineniya_iso_iz_nerzhaveyushchey_stali' => 'mi-category-icon_thirst',
	'svarochnye_materialy' => 'mi-category-icon_nineteen',
	'soedinitelnaya_armatura_i_fitingi' => 'mi-category-icon_nine',
	'sortovoy_prokat_nerzhaveyushchiy' => 'mi-category-icon_six',
	'truby_nerzhaveyushchie' => 'mi-category-icon_eight',
	// запасные коды / чёрный металлопрокат
	'armatura' => 'mi-category-icon_first',
	'balka_dvutavrovaya' => 'mi-category-icon_second',
	'shveller' => 'mi-category-icon_seventeen',
	'ugolok_stalnoy' => 'mi-category-icon_fifteen',
	'truba_kruglaya' => 'mi-category-icon_eight',
	'truba_profilnaya' => 'mi-category-icon_fourteen',
	'polosa_stalnaya' => 'mi-category-icon_ten',
	'kvadrat' => 'mi-category-icon_fourth',
	'krug_stalnoy' => 'mi-category-icon_six',
	'shestigrannik_stalnoy' => 'mi-category-icon_eighteen',
	'list_stalnoy' => 'mi-category-icon_seven',
	'otvody' => 'mi-category-icon_nine',
	'setka' => 'mi-category-icon_tvelwe',
	'provoloka' => 'mi-category-icon_eleven',
	'elektrody' => 'mi-category-icon_nineteen',
	'nerzhaveyushchaya_stal' => 'mi-category-icon_eight',
	'1_1_nerzhaveyushchaya_stal' => 'mi-category-icon_eight',
	'nekonditsiya' => 'mi-category-icon_tw',
);

$iconByNameHint = array(
	'лист' => 'mi-category-icon_seven',
	'труб' => 'mi-category-icon_eight',
	'круг' => 'mi-category-icon_six',
	'квадрат' => 'mi-category-icon_fourth',
	'уголок' => 'mi-category-icon_fifteen',
	'сетк' => 'mi-category-icon_tvelwe',
	'проволок' => 'mi-category-icon_eleven',
	'электрод' => 'mi-category-icon_nineteen',
	'свар' => 'mi-category-icon_nineteen',
	'метиз' => 'mi-category-icon_eleven',
	'перилл' => 'mi-category-icon_fifteen',
	'перил' => 'mi-category-icon_fifteen',
	'фитинг' => 'mi-category-icon_nine',
	'запорн' => 'mi-category-icon_nine',
	'соединит' => 'mi-category-icon_nine',
	'резьбов' => 'mi-category-icon_thirst',
	'сортов' => 'mi-category-icon_six',
	'заказн' => 'mi-category-icon_twenty',
	'арматур' => 'mi-category-icon_first',
	'балка' => 'mi-category-icon_second',
	'швеллер' => 'mi-category-icon_seventeen',
	'полос' => 'mi-category-icon_ten',
	'шестигран' => 'mi-category-icon_eighteen',
	'профильн' => 'mi-category-icon_fourteen',
);

if (!empty($arResult['SECTIONS']))
{
	foreach ($arResult['SECTIONS'] as &$arSection)
	{
		$icon = trim((string)($arSection['UF_ICON'] ?? ''));
		if ($icon !== '' && strpos($icon, 'category-icon_') !== false)
		{
			continue;
		}

		$code = mb_strtolower((string)($arSection['CODE'] ?? ''));
		if ($code !== '' && isset($iconByCode[$code]))
		{
			$arSection['UF_ICON'] = $iconByCode[$code];
			continue;
		}

		$name = mb_strtolower((string)($arSection['NAME'] ?? ''));
		$matched = '';
		foreach ($iconByNameHint as $needle => $class)
		{
			if ($name !== '' && mb_strpos($name, $needle) !== false)
			{
				$matched = $class;
				break;
			}
		}
		$arSection['UF_ICON'] = $matched !== '' ? $matched : 'mi-category-icon_eight';
	}
	unset($arSection);
}

?>