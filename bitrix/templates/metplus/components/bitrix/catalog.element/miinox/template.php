<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$unit = $arResult['CARD_UNIT'];
$retail = $arResult['CARD_PRICE_RETAIL'];
$opt = $arResult['CARD_PRICE_OPT'];
$images = $arResult['CARD_IMAGES'];
$placeholder = SITE_TEMPLATE_PATH . '/img/static/product-placeholder.svg';
$mainImage = $images[0] ?? $placeholder;
$formatPrice = static function (?float $value) use ($unit): string {
    if ($value === null) {
        return '';
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
$detailHtml = $detailIsHtml ? $detail : nl2br(htmlspecialcharsbx($detail));
?>
<div class="product-card-page">
    <div class="product-card">
        <div class="product-card__gallery">
            <div class="product-card__stage">
                <img src="<?=htmlspecialcharsbx($mainImage)?>" alt="<?=htmlspecialcharsbx($name)?>" width="1200" height="1200">
            </div>
        </div>
        <div class="product-card__info">
            <h1 class="product-card__title"><?=htmlspecialcharsbx($name)?></h1>
            <div class="product-card__prices">
                <div class="product-card__price-row">
                    <span class="product-card__price-label">Цена розница</span>
                    <span class="product-card__price" data-card-price="retail" data-value="<?=$retail !== null ? htmlspecialcharsbx((string)$retail) : ''?>">
                        <?=$retail !== null ? htmlspecialcharsbx($formatPrice($retail)) : 'по запросу'?>
                    </span>
                </div>
                <div class="product-card__price-row">
                    <span class="product-card__price-label">Цена опт</span>
                    <span class="product-card__price product-card__price--opt" data-card-price="opt" data-value="<?=$opt !== null ? htmlspecialcharsbx((string)$opt) : ''?>">
                        <?=$opt !== null ? htmlspecialcharsbx($formatPrice($opt)) : 'по запросу'?>
                    </span>
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
            <?php if ($unit['CODE'] === 'm'): ?>
                <p class="product-card__note">Продажа на метры. Порезка в размер — по запросу.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($detail !== '' || !empty($arResult['CARD_SPECS'])): ?>
    <div class="product-card__more">
        <?php if ($detail !== ''): ?>
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
    var root = document.currentScript.previousElementSibling;
    if (!root || !root.classList.contains('product-card-page')) {
        root = document.querySelector('.product-card-page');
    }
    if (!root) return;
    var input = root.querySelector('.product-card__qty-input');
    var sum = root.querySelector('[data-card-sum]');
    var retail = root.querySelector('[data-card-price="retail"]');
    var step = parseFloat(input.step) || 1;
    var min = parseFloat(input.min) || step;
    function money(value) {
        return Math.round(value).toLocaleString('ru-RU') + ' ₽';
    }
    function refresh() {
        var qty = parseFloat(String(input.value).replace(',', '.'));
        if (!isFinite(qty) || qty < min) qty = min;
        input.value = String(qty);
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
    input.addEventListener('input', refresh);
    refresh();
})();
</script>
