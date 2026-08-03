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
			'zakaznye_nerzh' => 'category-icon_twenty',
			'zapornaya_armatura_nerzhaveyushchaya' => 'category-icon_first',
			'komplektuyushchie_dlya_peril_iz_nerzhaveyushchey_stali' => 'category-icon_fifteen',
			'listy_nerzhaveyushchie' => 'category-icon_seven',
			'metizy_nerzhaveyushchie' => 'category-icon_eleven',
			'rezbovye_soedineniya_iso_iz_nerzhaveyushchey_stali' => 'category-icon_thirst',
			'svarochnye_materialy' => 'category-icon_nineteen',
			'soedinitelnaya_armatura_i_fitingi' => 'category-icon_nine',
			'sortovoy_prokat_nerzhaveyushchiy' => 'category-icon_six',
			'truby_nerzhaveyushchie' => 'category-icon_eight',
			'armatura' => 'category-icon_first',
			'balka_dvutavrovaya' => 'category-icon_second',
			'shveller' => 'category-icon_seventeen',
			'ugolok_stalnoy' => 'category-icon_fifteen',
			'truba_kruglaya' => 'category-icon_eight',
			'truba_profilnaya' => 'category-icon_fourteen',
			'polosa_stalnaya' => 'category-icon_ten',
			'kvadrat' => 'category-icon_fourth',
			'krug_stalnoy' => 'category-icon_six',
			'shestigrannik_stalnoy' => 'category-icon_eighteen',
			'list_stalnoy' => 'category-icon_seven',
			'otvody' => 'category-icon_nine',
			'setka' => 'category-icon_tvelwe',
			'provoloka' => 'category-icon_eleven',
			'elektrody' => 'category-icon_nineteen',
			'nerzhaveyushchaya_stal' => 'category-icon_eight',
			'1_1_nerzhaveyushchaya_stal' => 'category-icon_eight',
			'nekonditsiya' => 'category-icon_tw',
		);

		$code = mb_strtolower((string)$code);
		if ($code === '') {
			$code = miinoxCatalogIconCodeFromLink($link);
		}
		if ($code !== '' && isset($byCode[$code])) {
			return $byCode[$code];
		}

		return miinoxResolveIconByNameHints((string)$name, 'category-icon_eight', true);
	}
}

if (!function_exists('miinoxResolveMenuIcon')) {
	function miinoxResolveMenuIcon($name = '', $code = '', $link = '')
	{
		static $byCode = array(
			'zakaznye_nerzh' => 'menu-icon_sixteen',
			'zapornaya_armatura_nerzhaveyushchaya' => 'menu-icon_first',
			'komplektuyushchie_dlya_peril_iz_nerzhaveyushchey_stali' => 'menu-icon_fourth',
			'listy_nerzhaveyushchie' => 'menu-icon_eleven',
			'metizy_nerzhaveyushchie' => 'menu-icon_fourteen',
			'rezbovye_soedineniya_iso_iz_nerzhaveyushchey_stali' => 'menu-icon_tvelwe',
			'svarochnye_materialy' => 'menu-icon_fifteen',
			'soedinitelnaya_armatura_i_fitingi' => 'menu-icon_tvelwe',
			'sortovoy_prokat_nerzhaveyushchiy' => 'menu-icon_nine',
			'truby_nerzhaveyushchie' => 'menu-icon_fifth',
			'armatura' => 'menu-icon_first',
			'balka_dvutavrovaya' => 'menu-icon_second',
			'shveller' => 'menu-icon_third',
			'ugolok_stalnoy' => 'menu-icon_fourth',
			'truba_kruglaya' => 'menu-icon_fifth',
			'truba_profilnaya' => 'menu-icon_six',
			'polosa_stalnaya' => 'menu-icon_seven',
			'kvadrat' => 'menu-icon_eight',
			'krug_stalnoy' => 'menu-icon_nine',
			'shestigrannik_stalnoy' => 'menu-icon_ten',
			'list_stalnoy' => 'menu-icon_eleven',
			'otvody' => 'menu-icon_tvelwe',
			'setka' => 'menu-icon_thirteen',
			'provoloka' => 'menu-icon_fourteen',
			'elektrody' => 'menu-icon_fifteen',
			'nerzhaveyushchaya_stal' => 'menu-icon_sv',
			'1_1_nerzhaveyushchaya_stal' => 'menu-icon_sv',
			'nekonditsiya' => 'menu-icon_sv',
		);

		$code = mb_strtolower((string)$code);
		if ($code === '') {
			$code = miinoxCatalogIconCodeFromLink($link);
		}
		if ($code !== '' && isset($byCode[$code])) {
			return $byCode[$code];
		}

		return miinoxResolveIconByNameHints((string)$name, 'menu-icon_sv', false);
	}
}

if (!function_exists('miinoxResolveIconByNameHints')) {
	function miinoxResolveIconByNameHints($name, $default, $forCategory)
	{
		$name = mb_strtolower($name);
		if ($forCategory) {
			$map = array(
				'лист' => 'category-icon_seven',
				'труб' => 'category-icon_eight',
				'круг' => 'category-icon_six',
				'квадрат' => 'category-icon_fourth',
				'уголок' => 'category-icon_fifteen',
				'сетк' => 'category-icon_tvelwe',
				'проволок' => 'category-icon_eleven',
				'электрод' => 'category-icon_nineteen',
				'свар' => 'category-icon_nineteen',
				'метиз' => 'category-icon_eleven',
				'перил' => 'category-icon_fifteen',
				'фитинг' => 'category-icon_nine',
				'запорн' => 'category-icon_first',
				'соединит' => 'category-icon_nine',
				'резьбов' => 'category-icon_thirst',
				'сортов' => 'category-icon_six',
				'заказн' => 'category-icon_twenty',
				'арматур' => 'category-icon_first',
				'балка' => 'category-icon_second',
				'швеллер' => 'category-icon_seventeen',
				'полос' => 'category-icon_ten',
				'шестигран' => 'category-icon_eighteen',
				'профильн' => 'category-icon_fourteen',
			);
		} else {
			$map = array(
				'лист' => 'menu-icon_eleven',
				'труб' => 'menu-icon_fifth',
				'круг' => 'menu-icon_nine',
				'квадрат' => 'menu-icon_eight',
				'уголок' => 'menu-icon_fourth',
				'сетк' => 'menu-icon_thirteen',
				'проволок' => 'menu-icon_fourteen',
				'электрод' => 'menu-icon_fifteen',
				'свар' => 'menu-icon_fifteen',
				'метиз' => 'menu-icon_fourteen',
				'перил' => 'menu-icon_fourth',
				'фитинг' => 'menu-icon_tvelwe',
				'запорн' => 'menu-icon_first',
				'соединит' => 'menu-icon_tvelwe',
				'резьбов' => 'menu-icon_tvelwe',
				'сортов' => 'menu-icon_nine',
				'заказн' => 'menu-icon_sixteen',
				'арматур' => 'menu-icon_first',
				'балка' => 'menu-icon_second',
				'швеллер' => 'menu-icon_third',
				'полос' => 'menu-icon_seven',
				'шестигран' => 'menu-icon_ten',
				'профильн' => 'menu-icon_six',
				'нержав' => 'menu-icon_sv',
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
