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
?>
<div class="container">
    <div class="mi-cart-content_header">
        <div class="mi-cart-content_title">Заявка оформлена</div>
        <button type="button" class="mi-cart-close" aria-label="Закрыть"></button>
        <ul class="mi-cart-steps">
            <li class="mi-cart-step_item">Шаг 1</li>
            <li class="mi-cart-step_item mi-cart-step_item-second">Шаг 2</li>
            <li class="mi-cart-step_item active">Шаг 3</li>
        </ul>
    </div>
    <div class="mi-cart-content_body mi-cart-content_body-mod mi-text-center">
        <div class="mi-check-icon"></div>
        <div class="mi-thanks-text">
            Спасибо за Ваш заказ!
            <small>Специалист в ближайшее время с Вами свяжется и уточнит все детали</small>
        </div>
        <a href="" class="mi-main-btn js-back-site_2">Вернуться на сайт</a>
    </div>
</div>
