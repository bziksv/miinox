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
$this->setFrameMode(false);
?>
<div class="gost-section">
    <div class="container">
        <div class="gost-section_header">
            <div class="left-column">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:search.form",
                    "flat",
                    Array(
                        "PAGE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["search"]
                    ),
                    $component
                );?>
            </div>
            <div class="right-column">
                <?$APPLICATION->IncludeComponent("prime:main.feedback", "gost", Array(
                    "EVENT_MESSAGE_ID" => array('88'),	// Почтовые шаблоны для отправки письма
                    "IBLOCK_ID" => "34",	// Код информационного блока
                    "IBLOCK_TYPE" => "feedback",	// Тип информационного блока (используется только для проверки)
                    "OK_TEXT" => "Спасибо, ваше сообщение принято.",	// Сообщение, выводимое пользователю после отправки
                    "PROPERTY_CODE" => array(	// Поля формы
                        0 => "NAME",
                        1 => "PHONE",
                    ),
                    "COMPONENT_TEMPLATE" => "call"
                ),
                    false
                );?>
            </div>
        </div>
        <?$APPLICATION->IncludeComponent(
            "bitrix:search.page",
            "",
            Array(
				"USE_TITLE_RANK" => "Y",
                "arrWHERE" => Array("iblock_".$arParams["IBLOCK_TYPE"]),
                "arrFILTER" => Array("iblock_".$arParams["IBLOCK_TYPE"]),
                "SHOW_WHERE" => "N",
                "RESTART" => "Y",
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "SET_TITLE" => $arParams["SET_TITLE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "LIST_PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                "arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => Array($arParams["IBLOCK_ID"])
            ),
            $component
        );?>
        <p><a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"]?>"><?=GetMessage("T_NEWS_DETAIL_BACK")?></a></p>
    </div>
</div>

