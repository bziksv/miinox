# miinox.ru — документация проекта

## О проекте

Сайт на **1С-Битрикс** — каталог металлопроката / нержавейки (ООО «Металлинвест Инокс», **miinox.ru**).  
Шаблон и кастом: семейство **metplus** (Prime Ltd), кодовая база близка к metplus-msk / metplus-vrn.

| Параметр | Значение |
|----------|----------|
| CMS | 1С-Битрикс |
| Шаблон | `bitrix/templates/metplus/` |
| Кастомный код | `bitrix/php_interface/`, `include/`, `ajax/`, `catalog/` |
| Медиа товаров | `upload/` |
| Локальный URL | **http://localhost:8091/** |
| Локальная БД | `test_metplus6719` @ `127.0.0.1` (дамп `test_metplus6719.sql.gz`) |
| Git | [github.com/bziksv/miinox](https://github.com/bziksv/miinox) |
| Сервер | `155.212.171.103` |

### Бизнес-особенности (по коду)

- Каталог продукции (нержавейка / цветмет), SEF `/catalog/`
- Обмен с **1С** (модуль `askaron.pro1c`)
- AJAX-корзина и заказ (`ajax/`)
- Roistat visit в письмах заказов (`init.php` → `bxModifySaleMails`)

---

## Фирменный стиль (брендбук)

Эталон айдентики **«Металлинвест Инокс»** / **miinox.ru**. Источник: лист брендбука (не скрин сайта).

| Файл | Назначение |
|------|------------|
| `docs/brand/brandbook-miinox.png` | Референс фирменного стиля (лист брендбука) |
| `bitrix/templates/metplus/img/static/logo.svg` | Каноничный логотип (ромб + текст через центр) |
| `bitrix/templates/metplus/img/static/logo-header.svg` | Горизонтальный lockup для шапки (знак + подпись) |
| `bitrix/templates/metplus/img/static/logo-mark.svg` | Только ромб (favicon / узкие места) |
| `bitrix/templates/metplus/img/static/logo-*-white.svg` | Инверсия (белый) для тёмного фона |

### Логотип

- Символ: **ромб** (квадрат, повёрнутый на 45°) с плетёным / переплетённым орнаментом внутри
- Подпись под знаком:
  - кириллица: **«МЕТАЛЛИНВЕСТ ИНОКС»**
  - смешанный вариант: **«МЕТАЛЛИНВЕСТ INOX»**
- Варианты:
  - цветной (знак + текст брендового цвета на белом)
  - монохром (чёрный)
  - инверсия (белый на брендовом синем)

### Цвет бренда

| Формат | Значение |
|--------|----------|
| **HEX** | `#206380` |
| **RGB** | `32 / 99 / 128` |
| **CMYK** | `84 / 37 / 20 / 37` |

Оттенок: тёмный бирюзово-синий (teal/blue).

### Типографика

- В брендбуке указан **Fontin Sans CR** (кириллическая версия Fontin Sans).
- Свободно доступный Fontin Sans — **только латиница**, без кириллицы.
- В SVG-логотипе использован **Golos Text SemiBold** (геометрический гротеск с кириллицей) — контуры встроены в SVG.
- Файлы шрифтов: `docs/brand/fonts/` (`GolosText-*.ttf`, `FontinSans-Bold-Latin.ttf` для справки).
- Если появится лицензионный **Fontin Sans CR** — заменить контуры текста в логотипе.

### Носители (из макета)

Вывеска, бланк/letterhead, Instagram, визитка, футболка — показывают применение логотипа и цвета.

> При правках UI / шаблона ориентироваться на этот эталон: логотип, `#206380`, Fontin Sans CR.

---

## Сервер и окружения

### Правило работы

> **Сейчас работаем только локально** (`http://localhost:8091/`).  
> Если сказано «выкатить» / «задеплоить» — это означает **dev** (`dev.miinox.ru`), не prod.  
> **Prod** (`miinox.ru`) — только по явной отдельной просьбе.

| Окружение | Домен | Роль сейчас |
|-----------|-------|-------------|
| **Local** | localhost:8091 | Основная разработка |
| **Dev** (выкат) | dev.miinox.ru | Проверка на сервере |
| **Prod** | miinox.ru | Не трогаем без явного указания |

| Окружение | Домен | Путь на сервере | IP |
|-----------|-------|-----------------|-----|
| **Prod** | miinox.ru | `/var/www/miinox_ru_usr/data/www/miinox.ru` | 155.212.171.103 |
| **Dev** (выкат) | dev.miinox.ru | `/var/www/dev_miinox_r_usr/data/www/dev.miinox.ru` | 155.212.171.103 |

**Владельцы файлов на сервере:**

| Среда | User:Group |
|-------|------------|
| **Prod** | `miinox_ru_usr:miinox_ru_usr` |
| **Dev** | `dev_miinox_r_usr:dev_miinox_r_usr` |

**Workflow:** локально → dev (по команде «выкатить») → prod (только если явно попросили).

### SSH-доступ

```bash
ssh root@155.212.171.103
```

Рекомендуемая запись в `~/.ssh/config`:

```
Host miinox
    HostName 155.212.171.103
    User root
    IdentityFile ~/.ssh/id_ed25519
    IdentitiesOnly yes
```

### Деплой на dev

«Выкатить» = залить на **dev.miinox.ru**:

```bash
# Пример: шаблон
scp bitrix/templates/metplus/header.php \
    root@155.212.171.103:/var/www/dev_miinox_r_usr/data/www/dev.miinox.ru/bitrix/templates/metplus/

# Очистка кеша Битрикс + права
ssh root@155.212.171.103 '
  SITE=/var/www/dev_miinox_r_usr/data/www/dev.miinox.ru
  rm -rf "$SITE/bitrix/cache/"*
  chown -R dev_miinox_r_usr:dev_miinox_r_usr "$SITE/bitrix/cache" "$SITE/bitrix/managed_cache"
'
```

После любых действий от root — выставить владельца сайта (см. `.cursor/rules/server-permissions.mdc`).

---

## Архитектура

```
Пользователь
    ↓
bitrix/templates/metplus/   (header, footer, CSS, JS)
    ↓
catalog/index.php           (bitrix:catalog, iblock 39)
ajax/index.php              (корзина, заказ)
    ↓
bitrix/php_interface/init.php
    ├── priceDiscount / getGroupPriceForProduct
    ├── isDebug()
    └── bxModifySaleMails (PHONE, EMAIL, ADDRESS, Roistat)
```

Ключевые файлы:

| Файл | Назначение |
|------|------------|
| `bitrix/php_interface/init.php` | Хелперы цен, хук писем заказа |
| `catalog/index.php` | Каталог (iblock **39**, меню/фон — iblock **13**) |
| `ajax/index.php` | Роутер AJAX (`?component=`) |
| `bitrix/templates/metplus/` | Шаблон сайта |

---

## Каталог

| Параметр | Значение |
|----------|----------|
| Основной iblock | **39** — товары (`1c_catalog`) |
| Категории / фон | **13** — сетка на главной, фон раздела |
| SEF | `/catalog/` |
| Компонент | `bitrix:catalog` → шаблон `catalog` |
| Точка входа | `catalog/index.php` |

Левое меню: `.left.menu_ext.php`.

> **Важно:** iblock 13 и 39 — разные сущности. Главная/меню используют 13, коммерческий каталог — 39.

---

## Корзина и AJAX

**Роутер:** `ajax/index.php` → `?component=<name>`

| Endpoint | Файл | Назначение |
|----------|------|------------|
| `?component=add_cart` | `ajax/add_cart.php` | Добавить товар |
| `?component=cart_small` | `ajax/cart_small.php` | Мини-корзина в шапке |
| `?component=cart` | `ajax/cart.php` | Overlay-корзина |
| `?component=order` | `ajax/order.php` | Форма заказа |
| `?component=cache` | `ajax/cache.php` | Служебное |

Фронтенд: `bitrix/templates/metplus/js/main.js`.

---

## Инфоблоки (из кода)

| ID | Назначение |
|----|------------|
| 4 | Услуги (главная) |
| 5 | Слайдер главной (`slider_new`: IMG_DESKTOP/TABLET/MOBILE, LINK, ADS) |
| 13 | Категории на главной / фон каталога |
| **39** | **Основной каталог товаров (1С)** |

(Остальные разделы — news, articles, reviews, gost, vacancy и т.д. — по компонентам на страницах.)

---

## Шаблон `metplus`

```
bitrix/templates/metplus/
├── header.php, footer.php
├── css/, js/, libs/, sass/
├── components/
│   ├── bitrix/     — переопределения каталога, корзины…
│   └── prime/      — кастомные компоненты Prime
└── img/
```

### Интеграции (header/footer)

Ранее в шаблоне metplus были Яндекс.Метрика, Bitrix24 CRM button, prime.visit / prime-ltd incut — **удалены** (2026-08-03).  
Остаётся: Roistat (`roistat_visit` в письмах заказов через `init.php`).

Файлы верификации Google Search Console / Яндекс.Вебмастер (`google*.html`, `yandex_*.html`) — **удалены**.

### Кастомные модули

| Модуль | Назначение |
|--------|------------|
| `askaron.pro1c` | Обмен с 1С |
| `prime.smartbanners` | Умные баннеры |
| `prime.cleaner` | Утилита очистки |
| `kda.exportexcel` | Экспорт в Excel |
| `sng.secure` | Безопасность |

---

## Структура проекта

```
bitrix/templates/metplus/   — шаблон сайта
bitrix/php_interface/       — init.php, dbconn, 1С import/export
include/                    — телефон, логотип, копирайт…
catalog/                    — точка входа каталога
ajax/                       — AJAX корзины и заказа
about/, contact/, delivery/
services/, reviews/, gost/
news/, articles/, vacancy/
prays/                      — прайс-листы
html/                       — старые HTML-прототипы
dev/                        — отладка (не для prod)
upload/                     — медиа (gitignored)
docs/                       — документация
docs/brand/                 — брендбук (фирменный стиль miinox)
```

Корневые dot-файлы:

| Файл | Назначение |
|------|------------|
| `.top.menu.php` | Верхнее меню |
| `.left.menu_ext.php` | Выпадающий каталог |
| `.bottom.menu.php` | Меню футера |
| `.htaccess` | Apache: редиректы, ЧПУ |
| `.access.php` | Права доступа Bitrix |

---

## Локальная разработка

Без Docker. Homebrew **nginx** + **PHP 8.3 FPM**, общий **mysql@8.0** на `:3306` (без второго mysqld).  
Щадящий режим: `pm=ondemand`, `max_children=3`, `memory_limit=256M`, `opcache=64M`, `nginx worker_processes=1`.

```bash
./scripts/setup-local-db.sh    # импорт test_metplus6719.sql.gz (один раз)
./scripts/setup-local-db.sh --force  # пересоздать БД из дампа
./scripts/start-dev.sh         # nginx :8091 + php-fpm :9091
./scripts/stop-dev.sh          # остановка
```

| Параметр | Значение |
|----------|----------|
| URL | http://localhost:8091/ |
| Nginx | `.local/nginx/nginx.conf` → `.local/run/nginx.conf` |
| PHP-FPM | `.local/php/fpm.conf`, `pools.conf` (порт **9091**) |
| Учётные данные БД | `.local/db.env` (gitignored) |
| Локальный dbconn | `bitrix/php_interface/dbconn.local.php` (gitignored) |
| БД | `test_metplus6719` / user `miinox_local` |

При первом запуске `start-dev.sh` вызывает `apply-local-db-config.sh`, если `dbconn.local.php` отсутствует.

### Порты соседних проектов

| Проект | HTTP | php-fpm |
|--------|------|---------|
| almamed / др. | 8080 | — |
| vilmed | 8082 | 9082 |
| polimer | 8084 | — |
| lormag | 8085 | — |
| metplus-vrn.ru | 8086 | 9086 |
| oftalmag / insortex | 8087 | 9087 |
| metprof-vrn | 8088 | — |
| vrn-ehk | 8089 | 9089 |
| medplakaty | 8090 | — |
| **miinox** | **8091** | **9091** |

---

## Git

**Репозиторий:** [github.com/bziksv/miinox](https://github.com/bziksv/miinox)  
Исходный remote при копировании был `neeil1990/metplus-msk.ru` — рабочий remote: **bziksv/miinox**.

### Что НЕ коммитить

Секреты исключены в `.gitignore`:

- `bitrix/.settings.php` — пароль БД, crypto_key
- `bitrix/php_interface/dbconn.php`, `dbconn.local.php`
- `bitrix/license_key.php`
- `.local/`
- `upload/`, кеш, дампы (`*.sql`, `*.tar.gz`)

### Исключения из индексации Cursor

Настроены в `.cursorignore`:

- кеш Битрикс
- медиа (`upload/`)
- дампы и архивы
- бинарные файлы, ядро `bitrix/admin|js|css|…`
- чужие шаблоны (кастомный — `metplus`)

После изменения `.cursorignore`: **Cursor Settings → Indexing → Reindex**.

---

## Известные особенности

| # | Проблема | Где |
|---|----------|-----|
| 1 | Два iblock каталога (13 и 39) | `catalog/index.php`, главная |
| 2 | `isDebug()` перевёрнут — `true`, когда debug выключен | `init.php` |
| 3 | Дамп/конфиг БД ещё от metplus-msk (`test_metplus6719`) | `dbconn.php`, `.sql` |
| 4 | `robots.txt` указывает на `test.metplus-msk.ru` | корневой `robots.txt` |
| 5 | Полное ядро Битрикс в git — тяжёлый репозиторий | весь `bitrix/` |
| 6 | `include/company_name.php` выглядит как мусорный контент | проверить перед prod |

---

## Журнал изменений

| Дата | Что сделано |
|------|-------------|
| 2026-08-03 | Старт репо miinox: `.gitignore`, `.cursorignore`, `docs/PROJECT.md`, правила Cursor. Зафиксированы prod/dev пути и IP 155.212.171.103 |
| 2026-08-03 | Локалка :8091 / fpm :9091 (gentle ondemand×3, 256M). БД `test_metplus6719` (878 tables) на общем mysql@8.0 |
| 2026-08-03 | В `docs/PROJECT.md` и `docs/brand/` — фирменный стиль miinox (логотип, `#206380`, Fontin Sans CR). Удалены Bitrix24/метрики/файлы верификации вебмастеров |
| 2026-08-03 | Логотип miinox: SVG по брендбуку (`logo.svg`, `logo-header.svg`, `logo-mark.svg`), подключён в шапке, обновлены favicon и include |
| 2026-08-03 | Слайдер главной: порт `slider_new` из metplus-vrn (picture + WebP, `getImageWebpSrc`, патч `main.js`, свойства ИБ 5) |

### Шаблон записи

```markdown
### YYYY-MM-DD — краткое описание
- **Проблема:** ...
- **Решение:** ...
- **Файлы:** path/to/file.php
```
