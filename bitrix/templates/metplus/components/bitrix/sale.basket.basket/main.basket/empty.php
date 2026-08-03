<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
?>
<div class="container">
    <div class="cart-content_header">
        <div class="cart-content_title">Состав корзины</div>
        <button type="button" class="cart-close" aria-label="Закрыть"></button>
        <ul class="cart-steps">
            <li class="cart-step_item active active-mod">Шаг 1</li>
            <li class="cart-step_item">Шаг 2</li>
            <li class="cart-step_item">Шаг 3</li>
        </ul>
    </div>

    <div class="cart-content_body">
        <div class="empty-cart" style="text-align: center">
            <img src="<?=$this->GetFolder()?>/images/empty_cart.svg" style="margin-bottom: 30px">
            <div class="bx-sbb-empty-cart-text"><?=Loc::getMessage("SBB_EMPTY_BASKET_TITLE")?></div>
            <?
            if (!empty($arParams['EMPTY_BASKET_HINT_PATH']))
            {
                ?>
                <div class="bx-sbb-empty-cart-desc">
                    <?=Loc::getMessage(
                        'SBB_EMPTY_BASKET_HINT',
                        [
                            '#A1#' => '<a href="'.$arParams['EMPTY_BASKET_HINT_PATH'].'">',
                            '#A2#' => '</a>',
                        ]
                    )?>
                </div>
                <?
            }
            ?>
        </div>
    </div>

    <div class="cart-content_footer">
        <div class="left-column">
            <a href="" class="gray-btn back-site_btn js_back-site">Вернуться к сайту</a>
        </div>
    </div>

</div>
