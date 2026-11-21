<?php
if(!$arParams['ID'])
    return false;
?>
<div class="to_order-main">
    <div class="to_order-text" data-text="Данная продукция находится на складе в<br/>Воронеже.<br/>Только под заказ!"></div>
    <div class="to_order-btn">
        <a href="#" class="mi-main-btn" id="inStockCat" data-cat_id="<?=$arParams['ID']?>">
            Я согласен, заказать <span class="glipf-cart"></span>
        </a>
    </div>
</div>
<div class="to_order-back"></div>

