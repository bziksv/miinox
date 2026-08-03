<?php

$arCustomPage = array (
  'PARENT_MENU' => 'global_menu_settings',
  'SORT' => '1780',
  'LANG' => 
  array (
    'ru' => 
    array (
      'MENU_TEXT' => 'Дополнительные настройки',
      'MENU_TITLE' => 'Дополнительные настройки',
      'PAGE_TITLE' => 'Дополнительные настройки',
    ),
    'en' => 
    array (
      'MENU_TEXT' => 'Additional settings',
      'MENU_TITLE' => 'Additional settings',
      'PAGE_TITLE' => 'Additional settings',
    ),
  ),
);

$arCustomSettings = array (
  1 => 
  array (
    'LANG' => 
    array (
      'ru' => 
      array (
        'NAME' => 'Видимость выпадающего меню каталог ',
        'TITLE' => 'Видимость выпадающего меню каталог ',
      ),
      'en' => 
      array (
        'NAME' => '',
        'TITLE' => '',
      ),
    ),
    'SORT' => '500',
    'FIELDS' => 
    array (
      1 => 
      array (
        'NAME' => 'show_catalog_menu',
        'SORT' => '500',
        'LANG' => 
        array (
          'ru' => 
          array (
            'NAME' => 'Страницы сайта ',
            'TOOLTIP' => 'Напишите относительные URL страниц сайта, на которых необходимо сделать меню "каталог" видимым для поисковых роботов.  Необходимо писать каждый URL с новой строки.  Пример:  /articles/nerzhaveyushchaya-stal-aisi/',
          ),
          'en' => 
          array (
            'NAME' => '',
            'TOOLTIP' => '',
          ),
        ),
        'TYPE' => 'textarea',
        'DEFAULT_VALUE' => '',
        'COLS' => '50',
        'ROWS' => '10',
      ),
    ),
  ),
);

?>