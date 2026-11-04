<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">

    <div class="left-column">
        <a href="/" class="gray-btn back-site_btn js_back-site">Вернуться к сайту</a>
    </div>
    <div class="right-column">
        <div class="cart-total-price">
            Итоговая стоимость: <span class="cart-total_sum" data-entity="basket-total-price">{{{PRICE_FORMATED}}}</span>
        </div>
        <a href="" class="main-btn checkout-btn js-checkout"><?=Loc::getMessage('SBB_ORDER')?></a>
    </div>

</script>