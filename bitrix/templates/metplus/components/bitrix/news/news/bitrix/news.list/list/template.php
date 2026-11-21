<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["ITEMS"])>0):?>
    <?foreach($arResult["ITEMS"] as $arItem):?>
    <div class="mi-sidebar_news-item">
        <div class="mi-sidebar_news-item_img">
            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                <img data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>">
            </a>
        </div>
        <div class="mi-sidebar_news-item_desc">
            <div class="mi-sidebar_news-item_title">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
            </div>
            <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                <span class="mi-sidebar_news-item_date"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
            <?endif?>
        </div>
    </div>
    <?endforeach;?>
<?endif?>


