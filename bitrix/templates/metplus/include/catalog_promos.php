<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$tpl = SITE_TEMPLATE_PATH;
?>
<li class="dropdown-menu_promos" aria-label="Акции">
	<div class="catalog-promos">
		<article class="catalog-promo-card">
			<div class="catalog-promo_heading">Акция месяца</div>
			<a class="catalog-promo" href="/services/">
				<img class="catalog-promo_img" src="<?=$tpl?>/img/static/catalog-promo.jpg" alt="">
				<span class="catalog-promo_body">
					<span class="catalog-promo_title">Порезка труб в&nbsp;размер</span>
					<span class="catalog-promo_text">Длина не кратна 6&nbsp;м — режем под заказ</span>
					<span class="catalog-promo_more">Подробнее</span>
				</span>
			</a>
		</article>
		<article class="catalog-promo-card">
			<div class="catalog-promo_heading">Акция дня</div>
			<a class="catalog-promo" href="/prays/price_metall.xls">
				<img class="catalog-promo_img" src="<?=$tpl?>/img/static/catalog-promo-sheets.jpg" alt="">
				<span class="catalog-promo_body">
					<span class="catalog-promo_title">Опт на лист и&nbsp;сортовой прокат</span>
					<span class="catalog-promo_text">Скидка зависит от объёма — уточнит менеджер</span>
					<span class="catalog-promo_more">Скачать прайс</span>
				</span>
			</a>
		</article>
	</div>
</li>
