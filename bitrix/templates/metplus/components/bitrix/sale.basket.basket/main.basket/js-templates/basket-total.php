<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">

    <div class="mi-left-column">
        <a href="/" class="mi-gray-btn mi-back-site_btn js_back-site">Вернуться к сайту</a>
    </div>
    <div class="mi-right-column">
        <div class="mi-cart-total-price">
            Итоговая стоимость: <span class="mi-cart-total_sum" data-entity="basket-total-price">{{{PRICE_FORMATED}}}</span>
        </div>
        <a href="" class="mi-main-btn mi-checkout-btn js-checkout"><?=Loc::getMessage('SBB_ORDER')?></a>
    </div>

</script>