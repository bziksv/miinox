<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @var CBitrixComponentTemplate $this */
$this->setFrameMode(true);

if (empty($arResult['HOME_WORLDS'])) {
	return;
}

/**
 * Короткое имя для витрины: убираем хвосты «нержавеющ…», не ломая корневые названия.
 */
$shortName = static function ($name) {
	$original = trim(preg_replace('/\s+/u', ' ', (string)$name));
	$n = $original;
	$n = preg_replace('/\s*из\s+нержавеющей\s+стали\s*/iu', '', $n);
	$n = preg_replace('/\s+нержавеющ\w*\b/iu', '', $n);
	$n = preg_replace('/\bнержавеющ\w*\s+/iu', '', $n);
	$n = preg_replace('/\s*электросварн\w*\s*/iu', ' ', $n);
	$n = trim(preg_replace('/\s+/u', ' ', $n));
	// «НЕРЖАВЕЮЩАЯ СТАЛЬ» не должна стать «СТАЛЬ»
	if ($n === '' || mb_strlen($n) < 4 || preg_match('/^(сталь|металл)$/iu', $n)) {
		return $original;
	}
	return $n;
};

$childShort = static function ($name) use ($shortName) {
	$n = $shortName($name);
	// «Труба круглая нерж…» → «Круглая»
	if (preg_match('/^Труба\s+(.+)$/iu', $n, $m)) {
		$n = $m[1];
	}
	$n = preg_replace('/\s*наржавеющ\w*/iu', '', $n);
	$n = trim(preg_replace('/\s+/u', ' ', $n));
	if ($n !== '' && function_exists('mb_strtoupper')) {
		$n = mb_strtoupper(mb_substr($n, 0, 1)) . mb_substr($n, 1);
	}
	return $n !== '' ? $n : $shortName($name);
};

$worldTitle = static function ($name, $theme) {
	if ($theme === 'steel') {
		return 'Нержавеющая сталь';
	}
	if ($theme === 'copper') {
		return 'Цветной металлопрокат';
	}
	$n = trim(preg_replace('/\s+/u', ' ', (string)$name));
	if (function_exists('mb_convert_case')) {
		return mb_convert_case(mb_strtolower($n), MB_CASE_TITLE, 'UTF-8');
	}
	return $n;
};

$iconFor = static function ($code, $name) {
	$hay = mb_strtolower($code . ' ' . $name);
	$icons = array(
		'труб' => '<ellipse cx="6.5" cy="12" rx="2.8" ry="6"/><path d="M6.5 6H17a2.8 6 0 0 1 0 12H6.5"/>',
		'лист' => '<path d="M3 8l9-4 9 4-9 4z"/><path d="M3 12l9 4 9-4"/><path d="M3 16l9 4 9-4"/>',
		'сортов' => '<circle cx="7.5" cy="7.5" r="3.5"/><rect x="14" y="4" width="6.5" height="7" rx="1"/><path d="M4 14v7h7"/><path d="M15 21h5.5v-6"/>',
		'фитинг' => '<path d="M3 8h9a5 5 0 0 1 5 5v8"/><path d="M3 5v6"/><path d="M14 21h6"/>',
		'арматур' => '<path d="M3 8h9a5 5 0 0 1 5 5v8"/><path d="M3 5v6"/><path d="M14 21h6"/>',
		'резьб' => '<path d="M3 8h9a5 5 0 0 1 5 5v8"/><path d="M3 5v6"/><path d="M14 21h6"/>',
		'перилл' => '<path d="M4 20V8M8 20V6M12 20V8M16 20V6M20 20V8"/><path d="M3 8h18"/>',
		'перил' => '<path d="M4 20V8M8 20V6M12 20V8M16 20V6M20 20V8"/><path d="M3 8h18"/>',
		'запорн' => '<circle cx="12" cy="14" r="5"/><path d="M12 9V4M8.5 4h7"/><path d="M2 14h5M17 14h5"/>',
		'свар' => '<path d="M13 2L4 14h7l-1 8 9-12h-7z"/>',
		'метиз' => '<path d="M12 3l7.8 4.5v9L12 21l-7.8-4.5v-9z"/><circle cx="12" cy="12" r="3"/>',
		'заказ' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 12h8M12 8v8"/>',
		'алюмин' => '<rect x="3.5" y="6" width="17" height="12" rx="2.5"/><rect x="8" y="9.5" width="8" height="5" rx="1"/>',
		'мед' => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1.4"/>',
		'латун' => '<path d="M5 19L15.5 8.5"/><path d="M9.5 21.5L20 11"/><circle cx="16.8" cy="7.2" r="1.2"/>',
		'бронз' => '<circle cx="12" cy="9" r="5.5"/><path d="M9 13.8L7.5 21l4.5-2.6 4.5 2.6-1.5-7.2"/>',
		'кондиц' => '<rect x="3" y="4.5" width="18" height="8" rx="2.2"/><path d="M7 8.5h10"/><path d="M7 16.5v2.5M12 16v4.5M17 16.5v2.5"/>',
		'тормоз' => '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="3"/>',
	);
	$path = '<circle cx="12" cy="12" r="7"/>';
	foreach ($icons as $needle => $svg) {
		if (mb_strpos($hay, $needle) !== false) {
			$path = $svg;
			break;
		}
	}
	return '<svg class="home-dual__ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
};

$displayNames = array(
	'soedinitelnaya_armatura_i_fitingi' => 'Фитинги',
	'tovary_dlya_montazha_konditsionerov' => 'Монтаж кондиционеров',
	'zapornaya_armatura_nerzhaveyushchaya' => 'Запорная арматура',
	'metizy_nerzhaveyushchie' => 'Метизы и крепёж',
);

// Порядок категорий как в макете (внутри мира)
$catOrder = array(
	'steel' => array(
		'truby_nerzhaveyushchie',
		'listy_nerzhaveyushchie',
		'sortovoy_prokat_nerzhaveyushchiy',
		'soedinitelnaya_armatura_i_fitingi',
		'zapornaya_armatura_nerzhaveyushchaya',
		'metizy_nerzhaveyushchie',
	),
	'copper' => array(
		'alyuminiy',
		'med',
		'latun',
		'bronza',
		'tovary_dlya_montazha_konditsionerov',
	),
);

$leads = array(
	'steel' => 'Трубы, листы, сортовой прокат, фитинги и крепёж. AISI 201, 304, 316, 430.',
	'copper' => 'Алюминий, медь, латунь и бронза. Листы, прутки, трубы, профиль.',
);

$ctaLabels = array(
	'steel' => 'Весь каталог нержавейки',
	'copper' => 'Весь каталог цветных металлов',
);

// Категории, которые уводим в «ещё», чтобы не забивать сетку
$moreCodes = array(
	'rezbovye_soedineniya_iso_iz_nerzhaveyushchey_stali' => true,
	'komplektuyushchie_dlya_peril_iz_nerzhaveyushchey_stali' => true,
	'svarochnye_materialy' => true,
	'nerzhaveyushchiy_metall_pod_zakaz' => true,
	'tsvetnoy_metalloprokat_pod_zakaz' => true,
	'nabor_dlya_remonta_tormoznoy_sistemy' => true,
);

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
$worldIndex = 0;
?>
<div class="home-dual" id="home-dual" data-hd-theme="brand" data-hd-layout="cols" data-hd-items="rows">
<?php foreach ($arResult['HOME_WORLDS'] as $world):
	$theme = $world['THEME'];
	$primary = array();
	$more = array();
	$byCode = array();
	foreach ($world['CHILDREN'] as $cat) {
		$code = mb_strtolower((string)($cat['CODE'] ?? ''));
		if (isset($moreCodes[$code])) {
			$more[] = $cat;
		} else {
			$byCode[$code] = $cat;
		}
	}
	if (!empty($catOrder[$theme])) {
		foreach ($catOrder[$theme] as $code) {
			if (isset($byCode[$code])) {
				$primary[] = $byCode[$code];
				unset($byCode[$code]);
			}
		}
	}
	foreach ($byCode as $cat) {
		$primary[] = $cat;
	}
	$titleTag = $worldIndex === 0 ? 'h1' : 'h2';
	$worldIndex++;
	?>
	<section class="home-dual__sec home-dual__sec_<?= htmlspecialcharsbx($theme) ?>" aria-labelledby="home-dual-<?= (int)$world['ID'] ?>">
		<div class="home-dual__inner">
			<<?= $titleTag ?> class="home-dual__title" id="home-dual-<?= (int)$world['ID'] ?>"><?= htmlspecialcharsbx($worldTitle($world['NAME'], $theme)) ?></<?= $titleTag ?>>
			<p class="home-dual__lead"><?= htmlspecialcharsbx($leads[$theme] ?? '') ?></p>
			<div class="home-dual__cats">
				<?php foreach ($primary as $cat):
					$this->AddEditAction($cat['ID'], $cat['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($cat['ID'], $cat['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
					$catUrl = (string)($cat['SECTION_PAGE_URL'] ?? '');
					$catCode = (string)($cat['CODE'] ?? '');
					$catName = (string)$cat['NAME'];
					$catLabel = isset($displayNames[$catCode]) ? $displayNames[$catCode] : $shortName($catName);
					?>
					<div class="home-dual__cat" id="<?= $this->GetEditAreaId($cat['ID']) ?>">
						<h2 class="home-dual__cat-title">
							<?= $iconFor($catCode, $catName) ?>
							<a href="<?= htmlspecialcharsbx($catUrl) ?>"><?= htmlspecialcharsbx($catLabel) ?></a>
						</h2>
						<?php if (!empty($cat['CHILDREN'])): ?>
							<ul class="home-dual__cat-list">
								<?php foreach ($cat['CHILDREN'] as $child):
									$childUrl = (string)($child['SECTION_PAGE_URL'] ?? '');
									?>
									<li><a href="<?= htmlspecialcharsbx($childUrl) ?>"><?= htmlspecialcharsbx($childShort($child['NAME'])) ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="home-dual__more">
				<?php if (!empty($more)):
					$moreLinks = array();
					foreach ($more as $m) {
						$moreLinks[] = '<a href="' . htmlspecialcharsbx((string)$m['SECTION_PAGE_URL']) . '">' . htmlspecialcharsbx($shortName($m['NAME'])) . '</a>';
					}
					?>
					<p class="home-dual__more-text">Ещё: <?= implode(', ', $moreLinks) ?></p>
				<?php endif; ?>
				<?php if ($world['URL'] !== ''): ?>
					<a class="home-dual__cta" href="<?= htmlspecialcharsbx($world['URL']) ?>"><?= htmlspecialcharsbx($ctaLabels[$theme] ?? 'Весь каталог') ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php if ($theme === 'steel'): ?>
		<div class="home-dual__bridge" aria-hidden="true"></div>
	<?php endif; ?>
<?php endforeach; ?>
</div>
