<?php
/**
 * Какие фильтры показывать в разделе. «да» из матрицы каталога.
 * Ключ — символьный код раздела.
 */
return [
    'truba_kruglaya_nerzhaveyushchaya' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'DN_AKH', 'POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Круглые нержавеющие трубы
    'truba_kvadratnaya_nerzhaveyushchaya' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Квадратные нержавеющие трубы
    'truba_besshovnaya_nerzhaveyushchaya' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Бесшовные трубы
    'truba_pryamougolnaya_nerzhaveyushchaya' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Прямоугольные нержавеющие трубы
    'goryachekatannye_nerzhaveyushchie_listy' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Горячекатаные нержавеющие листы
    'kholodnokatannye_nerzhaveyushchie_listy' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Холоднокатаные нержавеющие листы
    'riflenye_nerzhaveyushchie_listy' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Рифлёные нержавеющие листы
    'krug_prutok_nerzhaveyushchiy' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Круг нержавеющий
    'kvadrat_nerzhaveyushchiy' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Квадрат нержавеющий
    'ugolok_nerzhaveyushchiy' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Уголок нержавеющий
    'polosa_nerzhaveyushchaya' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Полоса нержавеющая
    'otvody_truboprovodnye_nerzhaveyushchie' => ['TOLSHCHINA_AKH', 'SPOSOB_PRISOEDINENIYA_AKH', 'POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Отводы трубопроводные нержавеющие
    'mufta_soedinitelnaya_din_iz_nerzhaveyushchey_stali' => ['SPOSOB_PRISOEDINENIYA_AKH', 'DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Муфта соединительная din из нержавеющей стали
    'troyniki_nerzhaveyushchie' => ['TOLSHCHINA_AKH', 'SPOSOB_PRISOEDINENIYA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Тройники нержавеющие
    'perekhody_nerzhaveyushchie' => ['TOLSHCHINA_AKH', 'SPOSOB_PRISOEDINENIYA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Переходы нержавеющие
    'flantsy_nerzhaveyushchie' => ['DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Фланцы нержавеющие
    'khomuty_na_nozhke_iz_nerzhaveyushchey_stali' => ['DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Хомуты на ножке нержавеющие
    'nippeli_nerzhaveyushchie' => ['SPOSOB_PRISOEDINENIYA_AKH', 'DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Ниппели нержавеющие
    'bochonki_nerzhaveyushchie' => ['SPOSOB_PRISOEDINENIYA_AKH', 'DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Бочонки нержавеющие
    'amerikanki_nerzhaveyushchie' => ['SPOSOB_PRISOEDINENIYA_AKH', 'DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Американки нержавеющие
    'sgony_nerzhaveyushchie' => ['SPOSOB_PRISOEDINENIYA_AKH', 'DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Сгоны нержавющие
    'mufty_ravnoprokhodnye_nerzhaveyushchie' => ['SPOSOB_PRISOEDINENIYA_AKH', 'DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Муфты нержавеющие
    'krany_sharovye_iso_iz_nerzhaveyushchey_stali' => ['SPOSOB_PRISOEDINENIYA_AKH', 'DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Краны шаровые нержавеющие
    'zatvory_diskovye_iz_nerzhaveyushchey_stali' => ['SPOSOB_PRISOEDINENIYA_AKH', 'DN_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Затворы дисковые нержавеющие
    'gayki_shestigrannye_narzhaveyushchie' => ['MARKA_STALI_SPLAVA_AKH'], // Гайки шестигранные нержавеющие
    'shayby_nerzhaveyushchie' => ['MARKA_STALI_SPLAVA_AKH'], // Шайбы плоские нержавеющие
    'shpilki_nerzhaveyushchie' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Шпильки нержавеющие
    'soediniteli_poruchnya_nerzhaveyushchie' => ['POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Соединители поручня со стойкой нержавеющие
    'derzhateli_rigelya_nerzhaveyushchie' => ['POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Держатели ригеля нержавеющие
    'zaglushki_nerzhaveyushchie' => ['POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Заглушки нержавеющие
    'nizy_stoyki_nerzhaveyushchie' => ['POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Низ стойки нержавеющий
    'otvody_zerkalnye_nerzhaveyushchie' => ['TOLSHCHINA_AKH', 'SPOSOB_PRISOEDINENIYA_AKH', 'POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Отводы зеркальные нержавеющие
    'flanytsy_dlya_peril' => ['MARKA_STALI_SPLAVA_AKH'], // Фланцы для перил
    'elektrody_svarochnye' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Электроды
    'prutki_svarochnye' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Пруток сварочный
    'alyuminievyy_list' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'POVERKHNOST_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Лист алюминиевый
    'ugolki_alyuminievye' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Уголок алюминиевый
    'shina_alyuminievaya' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Шина алюминиевая
    'prutki_dyuralievye' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Пруток дюралевый
    'mednyy_list' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Лист медный
    'prutki_mednye' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Пруток медный
    'latunnyy_list' => ['TOLSHCHINA_AKH', 'DLINA_AKH', 'SHIRINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Лист латунный
    'prutki_latunnye' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Пруток латунный
    'shestigrannik_latunnyy' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Шестигранник латунный
    'prutki_bronzovye' => ['DLINA_AKH', 'MARKA_STALI_SPLAVA_AKH'], // Пруток бронзовый
    'truba_mednaya_dlya_konditsionerov' => ['TOLSHCHINA_AKH', 'DLINA_AKH'], // Труба медная для кондиционеров
    'komplekty_dlya_montazha_konditsionera' => ['DLINA_AKH'], // Комплекты для монтажа кондиционера
];
