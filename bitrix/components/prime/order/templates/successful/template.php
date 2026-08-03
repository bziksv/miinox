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
    <div class="cart-content_header">
        <div class="cart-content_title">Заявка оформлена</div>
        <button type="button" class="cart-close" aria-label="Закрыть"></button>
        <ul class="cart-steps">
            <li class="cart-step_item">Шаг 1</li>
            <li class="cart-step_item cart-step_item-second">Шаг 2</li>
            <li class="cart-step_item active">Шаг 3</li>
        </ul>
    </div>
    <div class="cart-content_body cart-content_body-mod text-center">
        <div class="check-icon"></div>
        <div class="thanks-text">
            Спасибо за Ваш заказ!
            <small>Специалист в ближайшее время с Вами свяжется и уточнит все детали</small>
        </div>
        <a href="" class="main-btn js-back-site_2">Вернуться на сайт</a>
    </div>
</div>
