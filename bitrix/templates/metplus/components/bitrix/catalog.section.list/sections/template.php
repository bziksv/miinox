<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

require_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/include/catalog_icons.php";

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

$resolvePic = static function (array $section): string {
	if (!empty($section['PICTURE']['SRC'])) {
		return (string)$section['PICTURE']['SRC'];
	}
	return miinoxResolveSubcategoryImageSrc(
		(string)($section['CODE'] ?? ''),
		(string)($section['NAME'] ?? ''),
		(string)($section['SECTION_PAGE_URL'] ?? '')
	);
};
?>

<? foreach ($arResult['SECTIONS'] as &$arSection):
	$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
	$subs = !empty($arSection['SUB_SECTION']) && is_array($arSection['SUB_SECTION'])
		? $arSection['SUB_SECTION']
		: array();
	$parentPic = $resolvePic($arSection);
	?>
	<section class="catalog-index__group" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
		<header class="catalog-index__group-head">
			<? if ($parentPic): ?>
				<span class="catalog-index__group-media" aria-hidden="true">
					<img src="<?=htmlspecialcharsbx($parentPic)?>?v=norm2" alt="" loading="lazy">
				</span>
			<? endif; ?>
			<div class="catalog-index__group-copy">
				<a class="catalog-index__group-title" href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME']?></a>
				<? if ($subs): ?>
					<p class="catalog-index__group-meta"><?=count($subs)?> направл<?=(count($subs) % 10 === 1 && count($subs) % 100 !== 11) ? 'ение' : 'ений'?></p>
				<? endif; ?>
			</div>
		</header>

		<? if ($subs): ?>
		<ul class="catalog-index__grid">
			<? foreach ($subs as $sub):
				$pic = $resolvePic($sub);
				?>
			<li class="catalog-index__card">
				<a class="catalog-index__card-link" href="<?=$sub['SECTION_PAGE_URL']?>">
					<span class="catalog-index__card-media">
						<? if ($pic): ?>
							<img src="<?=htmlspecialcharsbx($pic)?>?v=norm2" alt="<?=htmlspecialcharsbx($sub['NAME'])?>" loading="lazy">
						<? endif; ?>
					</span>
					<span class="catalog-index__card-title"><?=$sub['NAME']?></span>
				</a>
			</li>
			<? endforeach; ?>
		</ul>
		<? else: ?>
		<a class="catalog-index__solo" href="<?=$arSection['SECTION_PAGE_URL']?>">
			<span class="catalog-index__card-media">
				<? if ($parentPic): ?>
					<img src="<?=htmlspecialcharsbx($parentPic)?>" alt="<?=htmlspecialcharsbx($arSection['NAME'])?>" loading="lazy">
				<? endif; ?>
			</span>
			<span class="catalog-index__card-title"><?=$arSection['NAME']?></span>
		</a>
		<? endif; ?>
	</section>
<? endforeach; ?>
