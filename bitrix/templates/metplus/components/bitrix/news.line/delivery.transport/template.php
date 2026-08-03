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
<div class="recommended-transportation_content">
    <div class="title-h3"><?=$arResult['BLOCK']['NAME']?></div>
    <div class="row">
        <?foreach($arResult["ITEMS"] as $key => $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="col-md-4 transport-column">
            <div class="transport-item">
                <span class="transport-item_title"><?=$arItem['NAME']?> </span>
                <div class="transport-item_img transport-item_img-mod">
                    <img data-src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['NAME']?>">
                </div>
            </div>
        </div>
        <?endforeach;?>
    </div>
    <div class="recommended-transportation_footer">
        <div class="left-column">
			<p>Для вашего удобства мы оперативно доставляем нержавеющий и цветной металлопрокат по Москве, Московской области и всей территории РФ.</p>
			<p>Стоимость доставки рассчитывается индивидуально в зависимости от расстояния и веса заказа. В стоимость включена загрузка (до 60 мин.) и разгрузка автомобиля силами клиента (до 60 мин). Плюс 1 час въезд в ТТК.</p> 
			<p>Задержка транспорта свыше этого времени, оплачивается по факту простоя из расчета стоимости часа.</p>
			<p>Прайс-лист на доставку по Москве:</p>

			<table width="100%" border="1">
    <tbody>
       
        <tr style="text-align: center; padding: 7px;">
            <td>Авто</td>
            <td>Габариты кузова (м)</td>
            <td>Мин. т/р</td>
            <td>р/час</td>
            <td>р/км за мкад</td>
        </tr>
        
        <tr style="text-align: center; padding: 7px;">
            <td>До 1,5 тн</td>
            <td>4 </td>
            <td>5000</td>
            <td>850</td>
            <td>25</td>
        </tr>
        
        <tr style="text-align: center; padding: 7px;">
            <td>До 3 тн</td>
            <td>6</td>
            <td>7000</td>
            <td>950</td>
            <td>35</td>
        </tr>
        
        <tr style="text-align: center; padding: 7px;">
            <td>До 5 тн</td>
            <td>6</td>
            <td>11000</td>
            <td>1200</td>
            <td>45</td>
        </tr>
        
        <tr style="text-align: center; padding: 7px;">
            <td>До 10 тн</td>
            <td>8</td>
            <td>13000</td>
            <td>1500</td>
            <td>55</td>
        </tr>
        
        <tr style="text-align: center; padding: 7px;">
            <td>До 20 тн</td>
            <td>13</td>
            <td>17000</td>
            <td>1900</td>
            <td>65</td>
        </tr>
    </tbody>
</table>
<br>
			<p>Более детально ознакомиться с условиями доставки помогут менеджеры компании «Металлинвест Инокс» по номеру <a href="tel:+74952128506">+7 (495) 212-85-06</a></p>
			<p>Обращаем Ваше внимание, место выгрузки металлопроката и беспрепятственный проезд к нему клиент должен обеспечить самостоятельно!</p>

        </div>
        <div class="right-column">
            <div class="price-list_box">
                <div class="price-list_box-content">
                    <img alt="alt" src="<?=SITE_TEMPLATE_PATH?>/img/static/price-list_img.jpg">
					<a href="/prays/prays_dostavka.pdf" class="download-price_btn main-btn"><span class="glipf-download"></span>Скачать прайс</a>
                </div>
            </div>
        </div>
    </div>
</div>

