<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$cartUrl = !empty($arParams['PATH_TO_BASKET']) ? $arParams['PATH_TO_BASKET'] : '/cart/';
?>
<a href="<?=htmlspecialcharsbx($cartUrl)?>">
    <span class="glipf-cart"></span>
    <span class="head-cart_number"><?=(int)$arResult['NUM_PRODUCTS']?></span>
</a>
