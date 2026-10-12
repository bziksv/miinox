<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

/**
 * Строим дерево: мир (depth 1) → категории (depth 2) → подкатегории (depth 3).
 */
$byId = array();

foreach ($arResult['SECTIONS'] as $section) {
	$id = (int)$section['ID'];
	$section['CHILDREN'] = array();
	$byId[$id] = $section;
}

$childIds = array();
foreach ($byId as $id => $section) {
	$parentId = (int)($section['IBLOCK_SECTION_ID'] ?? 0);
	if ($parentId > 0 && isset($byId[$parentId])) {
		$childIds[$parentId][] = $id;
	}
}

$buildChildren = static function ($parentId) use (&$buildChildren, &$byId, &$childIds) {
	$out = array();
	if (empty($childIds[$parentId])) {
		return $out;
	}
	foreach ($childIds[$parentId] as $cid) {
		$node = $byId[$cid];
		$node['CHILDREN'] = $buildChildren($cid);
		$out[] = $node;
	}
	return $out;
};

$arResult['HOME_WORLDS'] = array();

foreach ($byId as $id => $section) {
	if ((int)$section['DEPTH_LEVEL'] !== 1) {
		continue;
	}
	$code = mb_strtolower(trim((string)($section['CODE'] ?? '')));
	$theme = 'steel';
	$nameLower = mb_strtolower((string)$section['NAME']);
	if (
		strpos($code, 'tsvet') !== false
		|| strpos($code, 'cvet') !== false
		|| strpos($nameLower, 'цвет') !== false
	) {
		$theme = 'copper';
	}

	$arResult['HOME_WORLDS'][] = array(
		'ID' => $id,
		'NAME' => trim((string)$section['NAME']),
		'CODE' => $code,
		'URL' => (string)($section['SECTION_PAGE_URL'] ?? ''),
		'THEME' => $theme,
		'CHILDREN' => $buildChildren($id),
	);
}
