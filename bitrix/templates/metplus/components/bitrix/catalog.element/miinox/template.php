<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$unit = $arResult['CARD_UNIT'];
$retail = $arResult['CARD_PRICE_RETAIL'];
$opt = $arResult['CARD_PRICE_OPT'];
$images = $arResult['CARD_IMAGES'] ?: [SITE_TEMPLATE_PATH . '/img/static/product-ph-1.svg'];
$formatPrice = static function (?float $value) use ($unit): string {
    if ($value === null) {
        return 'по запросу';
    }
    return number_format($value, 0, ',', ' ') . ' ₽/' . $unit['SHORT'];
};
$name = (string)$arResult['NAME'];
$detail = trim((string)($arResult['DETAIL_TEXT'] ?? ''));
if ($detail === '') {
    $detail = trim((string)($arResult['PREVIEW_TEXT'] ?? ''));
}
$detailIsHtml = ($arResult['DETAIL_TEXT_TYPE'] ?? '') === 'html'
    || stripos($detail, '<br') !== false
    || stripos($detail, '<p') !== false;
$detailHtml = $detail === '' ? '' : ($detailIsHtml ? $detail : nl2br(htmlspecialcharsbx($detail)));
$limited = !empty($arResult['CARD_STOCK_LIMITED']);
$factNames = ['Диаметр', 'Толщина', 'Длина', 'Ширина', 'Марка стали/сплава', 'Поверхность', 'DN', 'Форма'];
$facts = [];
foreach ($arResult['CARD_SPECS'] as $spec) {
    if (in_array($spec['NAME'], $factNames, true)) {
        $facts[] = $spec;
    }
}
?>
<div class="product-card-page">
    <ul class="breadcrumbs product-card__crumbs">
        <?php foreach ($arResult['CARD_CRUMBS'] as $index => $crumb): ?>
            <li>
                <?php if ($crumb['LINK'] !== '' && $index < count($arResult['CARD_CRUMBS']) - 1): ?>
                    <a href="<?=htmlspecialcharsbx($crumb['LINK'])?>"><?=htmlspecialcharsbx($crumb['TITLE'])?></a>
                <?php else: ?>
                    <?=htmlspecialcharsbx($crumb['TITLE'])?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="product-card">
        <div class="product-card__gallery">
            <div class="product-card__stage">
                <img src="<?=htmlspecialcharsbx($images[0])?>" alt="<?=htmlspecialcharsbx($name)?>" width="1200" height="900">
            </div>
            <?php if (count($images) > 1): ?>
            <div class="product-card__thumbs">
                <?php foreach ($images as $index => $src): ?>
                <button type="button" class="product-card__thumb<?=$index === 0 ? ' is-current' : ''?>" data-card-view="<?=htmlspecialcharsbx($src)?>" aria-label="Фото <?=$index + 1?>">
                    <img src="<?=htmlspecialcharsbx($src)?>" alt="" width="300" height="225">
                </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="product-card__info">
            <h1 class="product-card__title"><?=htmlspecialcharsbx($name)?></h1>

            <?php if ($facts): ?>
            <ul class="product-card__facts">
                <?php foreach ($facts as $fact): ?>
                <li>
                    <span><?=htmlspecialcharsbx($fact['NAME'])?></span>
                    <b><?=htmlspecialcharsbx(implode(', ', $fact['VALUES']))?></b>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <div class="product-card__prices">
                <div class="product-card__price-main">
                    <span class="product-card__price-label">Розница</span>
                    <span class="product-card__price" data-card-price="retail" data-value="<?=$retail !== null ? htmlspecialcharsbx((string)$retail) : ''?>"><?=htmlspecialcharsbx($formatPrice($retail))?></span>
                </div>
                <div class="product-card__price-side">
                    <span class="product-card__price-label">Опт</span>
                    <span class="product-card__price product-card__price--opt"><?=htmlspecialcharsbx($formatPrice($opt))?></span>
                    <span class="product-card__stock<?=$limited ? ' is-limited' : ''?>"><?=$limited ? 'Количество ограничено' : 'В наличии на складе'?></span>
                </div>
            </div>

            <div class="product-card__buy">
                <div class="product-card__qty">
                    <button type="button" class="product-card__qty-btn" data-card-qty="-1" aria-label="Меньше">−</button>
                    <input class="product-card__qty-input" type="number" name="pieces" min="<?=$unit['CODE'] === 'pc' ? '1' : '0.01'?>" step="<?=$unit['CODE'] === 'pc' ? '1' : '0.01'?>" value="1" aria-label="Количество, <?=$unit['LABEL']?>">
                    <span class="product-card__qty-unit"><?=htmlspecialcharsbx($unit['SHORT'])?></span>
                    <button type="button" class="product-card__qty-btn" data-card-qty="1" aria-label="Больше">+</button>
                </div>
                <button type="button" class="product-card__add" id="<?=(int)$arResult['ID']?>">В корзину</button>
            </div>
            <p class="product-card__total">Итого: <b data-card-sum>—</b></p>
            <ul class="product-card__perks">
                <li>Единица продажи — <?=htmlspecialcharsbx($unit['LABEL'])?></li>
                <?php if ($unit['CODE'] === 'm'): ?>
                <li>Порезка в размер — по запросу</li>
                <?php endif; ?>
                <li>Доставка и самовывоз — по запросу</li>
            </ul>
        </div>
    </div>

    <?php if ($detailHtml !== '' || !empty($arResult['CARD_SPECS'])): ?>
    <div class="product-card__more">
        <?php if ($detailHtml !== ''): ?>
        <section class="product-card__desc">
            <h2>Описание</h2>
            <div class="product-card__text"><?=$detailHtml?></div>
        </section>
        <?php endif; ?>
        <?php if (!empty($arResult['CARD_SPECS'])): ?>
        <section class="product-card__specs">
            <h2>Характеристики</h2>
            <table class="product-card__spec">
                <?php foreach ($arResult['CARD_SPECS'] as $spec): ?>
                <tr>
                    <th><?=htmlspecialcharsbx($spec['NAME'])?></th>
                    <td><?=htmlspecialcharsbx(implode(', ', $spec['VALUES']))?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <th>Единица</th>
                    <td><?=htmlspecialcharsbx($unit['LABEL'])?></td>
                </tr>
            </table>
        </section>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<script>
(function () {
    var root = document.querySelector('.product-card-page');
    if (!root) return;
    var input = root.querySelector('.product-card__qty-input');
    var sum = root.querySelector('[data-card-sum]');
    var retail = root.querySelector('[data-card-price="retail"]');
    var stage = root.querySelector('.product-card__stage img');
    var step = parseFloat(input.step) || 1;
    var min = parseFloat(input.min) || step;
    function money(value) {
        return Math.round(value).toLocaleString('ru-RU') + ' ₽';
    }
    function refresh() {
        var qty = parseFloat(String(input.value).replace(',', '.'));
        if (!isFinite(qty) || qty < min) qty = min;
        input.value = String(Math.round(qty * 1000) / 1000);
        var price = retail ? parseFloat(retail.getAttribute('data-value')) : NaN;
        sum.textContent = isFinite(price) ? money(qty * price) : 'по запросу';
    }
    root.querySelectorAll('[data-card-qty]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var qty = parseFloat(String(input.value).replace(',', '.')) || 0;
            qty += parseFloat(btn.getAttribute('data-card-qty')) * step;
            if (qty < min) qty = min;
            input.value = String(Math.round(qty * 1000) / 1000);
            refresh();
        });
    });
    root.querySelectorAll('[data-card-view]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            stage.src = btn.getAttribute('data-card-view');
            root.querySelectorAll('[data-card-view]').forEach(function (item) {
                item.classList.toggle('is-current', item === btn);
            });
        });
    });
    input.addEventListener('input', refresh);
    refresh();
})();
</script>
