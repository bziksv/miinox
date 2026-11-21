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
$this->setFrameMode(true);?>
<form action="<?=$arResult["FORM_ACTION"]?>" class="mi-head_form-search">
    <input type="text" name="q" class="mi-head_form-search_input" placeholder="Введите ваш запрос">
    <div class="mi-wrapper_submit-btn">
        <span class="glipf-search"></span>
        <input type="submit" name="s" class="mi-form-search_submit-btn" value="">
    </div>
</form>
