<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

/**
 * Фоллбэки иконок каталога, когда UF_ICON / UF_ICON_MENU с 1С пустые.
 * category-icon_* — спрайт главной, menu-icon_* — спрайт выпадающего меню.
 */
if (!function_exists('miinoxCatalogIconCodeFromLink')) {
	function miinoxCatalogIconCodeFromLink($link)
	{
		$link = (string)$link;
		if (preg_match('#/catalog/([^/]+)/?#u', $link, $m)) {
			return mb_strtolower($m[1]);
		}
		return '';
	}
}

if (!function_exists('miinoxResolveCategoryIcon')) {
	function miinoxResolveCategoryIcon($name = '', $code = '', $link = '')
	{
		static $byCode = array(
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
			'1_nerzhaveyushchaya_stal' => 'mi-category-icon_eight',
			'2_tsvetnye_metally' => 'mi-category-icon_six',
			'nekonditsiya' => 'mi-category-icon_tw',
		);

		$code = mb_strtolower((string)$code);
		if ($code === '') {
			$code = miinoxCatalogIconCodeFromLink($link);
		}
		if ($code !== '' && isset($byCode[$code])) {
			return $byCode[$code];
		}

		return miinoxResolveIconByNameHints((string)$name, 'mi-category-icon_eight', true);
	}
}

if (!function_exists('miinoxResolveMenuIcon')) {
	function miinoxResolveMenuIcon($name = '', $code = '', $link = '')
	{
		static $byCode = array(
			'zakaznye_nerzh' => 'mi-menu-icon_sixteen',
			'zapornaya_armatura_nerzhaveyushchaya' => 'mi-menu-icon_first',
			'komplektuyushchie_dlya_peril_iz_nerzhaveyushchey_stali' => 'mi-menu-icon_fourth',
			'listy_nerzhaveyushchie' => 'mi-menu-icon_eleven',
			'metizy_nerzhaveyushchie' => 'mi-menu-icon_fourteen',
			'rezbovye_soedineniya_iso_iz_nerzhaveyushchey_stali' => 'mi-menu-icon_tvelwe',
			'svarochnye_materialy' => 'mi-menu-icon_fifteen',
			'soedinitelnaya_armatura_i_fitingi' => 'mi-menu-icon_tvelwe',
			'sortovoy_prokat_nerzhaveyushchiy' => 'mi-menu-icon_nine',
			'truby_nerzhaveyushchie' => 'mi-menu-icon_fifth',
			'armatura' => 'mi-menu-icon_first',
			'balka_dvutavrovaya' => 'mi-menu-icon_second',
			'shveller' => 'mi-menu-icon_third',
			'ugolok_stalnoy' => 'mi-menu-icon_fourth',
			'truba_kruglaya' => 'mi-menu-icon_fifth',
			'truba_profilnaya' => 'mi-menu-icon_six',
			'polosa_stalnaya' => 'mi-menu-icon_seven',
			'kvadrat' => 'mi-menu-icon_eight',
			'krug_stalnoy' => 'mi-menu-icon_nine',
			'shestigrannik_stalnoy' => 'mi-menu-icon_ten',
			'list_stalnoy' => 'mi-menu-icon_eleven',
			'otvody' => 'mi-menu-icon_tvelwe',
			'setka' => 'mi-menu-icon_thirteen',
			'provoloka' => 'mi-menu-icon_fourteen',
			'elektrody' => 'mi-menu-icon_fifteen',
			'nerzhaveyushchaya_stal' => 'mi-menu-icon_sv',
			'1_1_nerzhaveyushchaya_stal' => 'mi-menu-icon_sv',
			'1_nerzhaveyushchaya_stal' => 'mi-menu-icon_sv',
			'2_tsvetnye_metally' => 'mi-menu-icon_nine',
			'nekonditsiya' => 'mi-menu-icon_sv',
		);

		$code = mb_strtolower((string)$code);
		if ($code === '') {
			$code = miinoxCatalogIconCodeFromLink($link);
		}
		if ($code !== '' && isset($byCode[$code])) {
			return $byCode[$code];
		}

		return miinoxResolveIconByNameHints((string)$name, 'mi-menu-icon_sv', false);
	}
}

if (!function_exists('miinoxResolveIconByNameHints')) {
	function miinoxResolveIconByNameHints($name, $default, $forCategory)
	{
		$name = mb_strtolower($name);
		if ($forCategory) {
			$map = array(
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
				'перил' => 'mi-category-icon_fifteen',
				'фитинг' => 'mi-category-icon_nine',
				'запорн' => 'mi-category-icon_first',
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
		} else {
			$map = array(
				'лист' => 'mi-menu-icon_eleven',
				'труб' => 'mi-menu-icon_fifth',
				'круг' => 'mi-menu-icon_nine',
				'квадрат' => 'mi-menu-icon_eight',
				'уголок' => 'mi-menu-icon_fourth',
				'сетк' => 'mi-menu-icon_thirteen',
				'проволок' => 'mi-menu-icon_fourteen',
				'электрод' => 'mi-menu-icon_fifteen',
				'свар' => 'mi-menu-icon_fifteen',
				'метиз' => 'mi-menu-icon_fourteen',
				'перил' => 'mi-menu-icon_fourth',
				'фитинг' => 'mi-menu-icon_tvelwe',
				'запорн' => 'mi-menu-icon_first',
				'соединит' => 'mi-menu-icon_tvelwe',
				'резьбов' => 'mi-menu-icon_tvelwe',
				'сортов' => 'mi-menu-icon_nine',
				'заказн' => 'mi-menu-icon_sixteen',
				'арматур' => 'mi-menu-icon_first',
				'балка' => 'mi-menu-icon_second',
				'швеллер' => 'mi-menu-icon_third',
				'полос' => 'mi-menu-icon_seven',
				'шестигран' => 'mi-menu-icon_ten',
				'профильн' => 'mi-menu-icon_six',
				'нержав' => 'mi-menu-icon_sv',
			);
		}

		foreach ($map as $needle => $class) {
			if ($name !== '' && mb_strpos($name, $needle) !== false) {
				return $class;
			}
		}
		return $default;
	}
}

if (!function_exists('miinoxResolveSubcategoryImageSrc')) {
	/**
	 * Фото товара для карточки подкатегории (когда PICTURE раздела пустая).
	 * Файлы: SITE_TEMPLATE_PATH/img/static/subcategory/{CODE}.{jpg|png|webp}
	 * @return string относительный URL или пустая строка
	 */
	function miinoxResolveSubcategoryImageSrc($code = '', $name = '', $link = '')
	{
		$code = mb_strtolower(trim((string)$code));
		if ($code === '') {
			$code = miinoxCatalogIconCodeFromLink($link);
		}

		$base = SITE_TEMPLATE_PATH . '/img/static/subcategory/';
		$root = rtrim((string)$_SERVER['DOCUMENT_ROOT'], '/') . $base;
		// png первым — cutout с прозрачностью; jpg без альфы даёт серые квадраты
		$exts = array('.png', '.webp', '.jpg', '.jpeg');

		if ($code !== '') {
			foreach ($exts as $ext) {
				if (is_file($root . $code . $ext)) {
					return $base . $code . $ext;
				}
			}
		}

		static $byHint = array(
			'затвор' => 'zatvory_diskovye_iz_nerzhaveyushchey_stali',
			'кран' => 'krany_sharovye_iso_iz_nerzhaveyushchey_stali',
			'запорн' => 'zapornaya_armatura_nerzhaveyushchaya',
			'перил' => 'komplektuyushchie_dlya_peril_iz_nerzhaveyushchey_stali',
			'ригел' => 'derzhateli_rigelya_nerzhaveyushchie',
			'заглуш' => 'zaglushki_nerzhaveyushchie',
			'стойк' => 'nizy_stoyki_nerzhaveyushchie',
			'поручн' => 'soediniteli_poruchnya_nerzhaveyushchie',
			'флан' => 'flantsy_nerzhaveyushchie',
			'рифлен' => 'riflenye_nerzhaveyushchie_listy',
			'горячекат' => 'goryachekatannye_nerzhaveyushchie_listy',
			'холоднокат' => 'kholodnokatannye_nerzhaveyushchie_listy',
			'цветн' => '2_tsvetnye_metally',
			'цветны' => '2_tsvetnye_metally',
			'медн' => '2_tsvetnye_metally',
			'алюмин' => '2_tsvetnye_metally',
			'латун' => '2_tsvetnye_metally',
			'бронз' => '2_tsvetnye_metally',
			'лист' => 'listy_nerzhaveyushchie',
			'гайк' => 'gayki_shestigrannye_narzhaveyushchie',
			'шайб' => 'shayby_nerzhaveyushchie',
			'шпил' => 'shpilki_nerzhaveyushchie',
			'метиз' => 'metizy_nerzhaveyushchie',
			'американ' => 'amerikanki_nerzhaveyushchie',
			'бочон' => 'bochonki_nerzhaveyushchie',
			'ниппел' => 'nippeli_nerzhaveyushchie',
			'сгон' => 'sgony_nerzhaveyushchie',
			'муфт' => 'mufty_ravnoprokhodnye_nerzhaveyushchie',
			'резьбов' => 'rezbovye_soedineniya_iso_iz_nerzhaveyushchey_stali',
			'электрод' => 'elektrody_svarochnye',
			'прутк' => 'prutki_svarochnye',
			'свар' => 'svarochnye_materialy',
			'тройник' => 'troyniki_nerzhaveyushchie',
			'переход' => 'perekhody_nerzhaveyushchie',
			'отвод' => 'otvody_truboprovodnye_nerzhaveyushchie',
			'хомут' => 'khomuty_na_nozhke_iz_nerzhaveyushchey_stali',
			'фитинг' => 'soedinitelnaya_armatura_i_fitingi',
			'соединит' => 'soedinitelnaya_armatura_i_fitingi',
			'квадратн' => 'truba_kvadratnaya_nerzhaveyushchaya',
			'прямоугол' => 'truba_pryamougolnaya_nerzhaveyushchaya',
			'кругл' => 'truba_kruglaya_nerzhaveyushchaya',
			'труб' => 'truby_nerzhaveyushchie',
			'квадрат' => 'kvadrat_nerzhaveyushchiy',
			'круг' => 'krug_prutok_nerzhaveyushchiy',
			'пруток' => 'krug_prutok_nerzhaveyushchiy',
			'полос' => 'polosa_nerzhaveyushchaya',
			'уголок' => 'ugolok_nerzhaveyushchiy',
			'сортов' => 'sortovoy_prokat_nerzhaveyushchiy',
			'заказн' => 'zakaznye_nerzh',
			'кров' => 'krovlya',
			'сантех' => 'santekhnika',
			'ковк' => 'kovka',
			'нержав' => 'nerzhaveyushchaya_stal',
		);

		$name = mb_strtolower((string)$name);
		foreach ($byHint as $needle => $fileCode) {
			if ($name === '' || mb_strpos($name, $needle) === false) {
				continue;
			}
			foreach ($exts as $ext) {
				if (is_file($root . $fileCode . $ext)) {
					return $base . $fileCode . $ext;
				}
			}
		}

		return '';
	}
}

// BC alias
if (!function_exists('miinoxResolveSubcategoryIconSrc')) {
	function miinoxResolveSubcategoryIconSrc($code = '', $name = '', $link = '')
	{
		return miinoxResolveSubcategoryImageSrc($code, $name, $link);
	}
}

if (!function_exists('miinoxApplyMenuIconFallbacks')) {
	function miinoxApplyMenuIconFallbacks(array &$menuLinks)
	{
		foreach ($menuLinks as &$link) {
			if (!is_array($link) || empty($link[3]) || !is_array($link[3])) {
				continue;
			}
			$name = (string)($link[0] ?? '');
			$url = (string)($link[1] ?? '');
			$params = &$link[3];

			$iconMenu = trim((string)($params['ICON_MENU'] ?? ''));
			if ($iconMenu === '' || strpos($iconMenu, 'menu-icon_') === false) {
				$params['ICON_MENU'] = miinoxResolveMenuIcon($name, '', $url);
			}

			$icon = trim((string)($params['ICON'] ?? ''));
			if ($icon === '' || strpos($icon, 'category-icon_') === false) {
				$params['ICON'] = miinoxResolveCategoryIcon($name, '', $url);
			}
		}
		unset($link);
	}
}
