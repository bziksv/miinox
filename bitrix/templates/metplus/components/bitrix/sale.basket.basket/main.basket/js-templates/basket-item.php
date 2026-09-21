<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $mobileColumns
 * @var array $arParams
 * @var string $templateFolder
 */
?>
<script id="basket-item-template" type="text/html">

	<tr id="basket-item-{{ID}}" data-entity="basket-item" data-id="{{ID}}">

		{{^SHOW_RESTORE}}

            <td>
                <span class="cart-table_mobile-text">Название товара</span>
                <span class="cart-item_title">{{NAME}}</span>
                {{#SHOW_CUT_NOTICE}}
                <span class="cart-cut-notice">Потребуется порезка — длина не кратна 6&nbsp;м</span>
                {{/SHOW_CUT_NOTICE}}
            </td>

            <?
            if (!empty($arParams['PRODUCT_BLOCKS_ORDER']))
            {
                foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName)
                {
                    switch (trim((string)$blockName))
                    {
                        case 'columns':
                            ?>
                            {{#COLUMN_LIST}}
                                {{#IS_TEXT}}
                                <td>
                                    <span class="cart-table_mobile-text">{{NAME}}</span>
                                    {{VALUE}}
                                </td>
                                {{/IS_TEXT}}
                            {{/COLUMN_LIST}}
                            <?
                            break;
                    }
                }
            }
            ?>

            <td>
                <span class="cart-table_mobile-text">Цена</span>
                <span id="basket-item-price-{{ID}}">{{{PRICE_FORMATED}}}</span>
            </td>

            <td>
                <span class="cart-table_mobile-text">Количество</span>
                <div class="cart-qty">
                    <div class="wrapper-counter-btn" data-entity="basket-item-quantity-block">
                        <button class="counter-back" data-entity="basket-item-quantity-minus"></button>
                        <input type="text" value="{{QUANTITY}}" class="product-count" {{#NOT_AVAILABLE}} disabled="disabled"{{/NOT_AVAILABLE}}
                        data-value="{{QUANTITY}}" data-entity="basket-item-quantity-field"
                        id="basket-item-quantity-{{ID}}">
                        <button class="counter-forward" data-entity="basket-item-quantity-plus"></button>
                    </div>
                    {{#MEASURE_SHORT}}<span class="cart-qty_unit">{{MEASURE_SHORT}}</span>{{/MEASURE_SHORT}}
                </div>
            </td>

            <td>
                <span class="cart-table_mobile-text">Сумма</span>
                <span id="basket-item-sum-price-{{ID}}">{{{SUM_PRICE_FORMATED}}}</span>
            </td>

            <td>
                <div class="cart-item_delete">
                    <span class="glipf-delete" data-entity="basket-item-delete"></span>
                </div>
            </td>

		{{/SHOW_RESTORE}}
	</tr>
</script>