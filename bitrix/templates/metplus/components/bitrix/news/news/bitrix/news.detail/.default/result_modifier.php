<?php
if (! $arResult['PROPERTIES']['PICS_NEWS']['VALUE']) {
	$arResult['PROPERTIES']['PICS_NEWS']['VALUE'] = [];
}

if(count($arResult['PROPERTIES']['PICS_NEWS']['VALUE']) > 0){
    $arResult['IMAGES'] = array_map(function ($img){
        return CFile::GetPath($img);
    }, $arResult['PROPERTIES']['PICS_NEWS']['VALUE']);
}
