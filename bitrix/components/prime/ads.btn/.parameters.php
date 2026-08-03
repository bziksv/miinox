<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    'PARAMETERS' => [
        'DESCRIPTION' => [
            'NAME' => 'Описание',
            'TYPE' => 'STRING',
			"MULTIPLE" => "N",
			"DEFAULT" => "",
        ],
        'SHOW' => [
            'NAME' => 'Показать кнопку',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
    ],
];
?>
