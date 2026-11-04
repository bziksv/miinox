<?php
define('NEED_AUTH', true);

$orderId = 0;
if (preg_match('#/personal/order/(\d+)/?#', (string)($_SERVER['REQUEST_URI'] ?? ''), $match)) {
    $orderId = (int)$match[1];
}
if ($orderId <= 0) {
    require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
    LocalRedirect('/personal/');
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

global $USER, $APPLICATION;

$formatMoney = static function (float $value): string {
    $cents = abs($value - round($value)) > 0.001;
    return number_format($value, $cents ? 2 : 0, ',', ' ') . ' ₽';
};

$formatQty = static function (float $value): string {
    $text = number_format($value, 3, ',', ' ');
    return rtrim(rtrim($text, '0'), ',');
};

$formatDate = static function (string $value): string {
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $value, $match)) {
        return $match[3] . '.' . $match[2] . '.' . $match[1];
    }
    if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})/', $value, $match)) {
        return $match[1] . '.' . $match[2] . '.' . $match[3];
    }
    return $value;
};

$userId = (int)$USER->GetID();
$order = null;
$items = [];
$props = [];
$productUrls = [];
$sectionCodes = [];
$statusName = '';
$hasWeight = false;

$productCardUrl = static function (array $element) use (&$sectionCodes): string {
    $code = trim((string)($element['CODE'] ?? ''));
    $sectionId = (int)($element['IBLOCK_SECTION_ID'] ?? 0);
    if ($code === '' || $sectionId <= 0 || !CModule::IncludeModule('iblock')) {
        return '';
    }
    if (!isset($sectionCodes[$sectionId])) {
        $section = CIBlockSection::GetList([], ['ID' => $sectionId], false, ['ID', 'CODE'])->Fetch();
        $sectionCodes[$sectionId] = $section ? trim((string)$section['CODE']) : '';
    }
    if ($sectionCodes[$sectionId] === '') {
        return '';
    }
    return '/catalog/' . rawurlencode($sectionCodes[$sectionId]) . '/' . rawurlencode($code) . '/';
};

if ($userId > 0 && CModule::IncludeModule('sale')) {
    $order = CSaleOrder::GetList(
        [],
        ['ID' => $orderId, 'USER_ID' => $userId],
        false,
        ['nTopCount' => 1],
        ['ID', 'ACCOUNT_NUMBER', 'DATE_INSERT', 'STATUS_ID', 'PRICE', 'CURRENCY', 'PAYED', 'CANCELED', 'USER_DESCRIPTION']
    )->Fetch() ?: null;

    if ($order) {
        $status = CSaleStatus::GetList([], ['ID' => $order['STATUS_ID'], 'LID' => LANGUAGE_ID])->Fetch();
        $statusName = $status ? (string)$status['NAME'] : (string)$order['STATUS_ID'];
        if ((string)$order['CANCELED'] === 'Y') {
            $statusName = 'Отменён';
        }

        $basket = CSaleBasket::GetList(['ID' => 'ASC'], ['ORDER_ID' => $orderId]);
        while ($item = $basket->Fetch()) {
            $items[] = $item;
            if ((float)$item['WEIGHT'] > 0) {
                $hasWeight = true;
            }
        }

        $propRes = CSaleOrderPropsValue::GetList(['SORT' => 'ASC', 'ID' => 'ASC'], ['ORDER_ID' => $orderId]);
        while ($prop = $propRes->Fetch()) {
            $value = trim((string)$prop['VALUE']);
            if ($value === '') {
                continue;
            }
            $props[] = [
                'NAME' => (string)$prop['NAME'],
                'VALUE' => $value,
            ];
        }

        if (CModule::IncludeModule('iblock')) {
            $productIds = [];
            foreach ($items as $item) {
                $productId = (int)$item['PRODUCT_ID'];
                if ($productId > 0) {
                    $productIds[$productId] = $productId;
                }
            }
            if ($productIds) {
                $elementRes = CIBlockElement::GetList(
                    [],
                    ['ID' => array_values($productIds), 'ACTIVE' => 'Y'],
                    false,
                    false,
                    ['ID', 'CODE', 'IBLOCK_SECTION_ID']
                );
                while ($element = $elementRes->Fetch()) {
                    $url = $productCardUrl($element);
                    if ($url !== '') {
                        $productUrls[(int)$element['ID']] = $url;
                    }
                }
            }
            foreach ($items as $item) {
                $productId = (int)$item['PRODUCT_ID'];
                if ($productId <= 0 || isset($productUrls[$productId])) {
                    continue;
                }
                $element = null;
                $xmlId = trim((string)($item['PRODUCT_XML_ID'] ?? ''));
                if ($xmlId !== '') {
                    $element = CIBlockElement::GetList(
                        [],
                        ['IBLOCK_ID' => 40, 'ACTIVE' => 'Y', '=XML_ID' => $xmlId],
                        false,
                        ['nTopCount' => 1],
                        ['ID', 'CODE', 'IBLOCK_SECTION_ID']
                    )->Fetch() ?: null;
                }
                if (!$element) {
                    $name = trim((string)$item['NAME']);
                    if ($name !== '') {
                        $byName = CIBlockElement::GetList(
                            [],
                            ['IBLOCK_ID' => 40, 'ACTIVE' => 'Y', '=NAME' => $name],
                            false,
                            ['nTopCount' => 2],
                            ['ID', 'CODE', 'IBLOCK_SECTION_ID']
                        );
                        $first = $byName->Fetch() ?: null;
                        $second = $byName->Fetch() ?: null;
                        if ($first && !$second) {
                            $element = $first;
                        }
                    }
                }
                if ($element) {
                    $url = $productCardUrl($element);
                    if ($url !== '') {
                        $productUrls[$productId] = $url;
                    }
                }
            }
        }
    }
}

$number = $order ? (string)($order['ACCOUNT_NUMBER'] ?: $order['ID']) : '';
$APPLICATION->SetTitle($order ? 'Заказ ' . $number : 'Заказ не найден');
$comment = $order ? trim((string)$order['USER_DESCRIPTION']) : '';
?>
<div class="account-page">
    <div class="account-page__inner">
        <header class="account-head">
            <div>
                <a class="account-back" href="/personal/">К заказам</a>
                <h1><?=$order ? 'Заказ ' . htmlspecialcharsbx($number) : 'Заказ не найден'?></h1>
                <?php if ($order): ?>
                    <p><?=htmlspecialcharsbx($formatDate((string)$order['DATE_INSERT']))?> · <?=htmlspecialcharsbx($statusName)?></p>
                <?php endif; ?>
            </div>
            <?php if ($order): ?>
                <div class="account-order-sum"><?=htmlspecialcharsbx($formatMoney((float)$order['PRICE']))?></div>
            <?php endif; ?>
        </header>

        <?php if (!$order): ?>
            <section class="account-card">
                <p class="account-empty">Этот заказ вам недоступен или его нет.</p>
            </section>
        <?php else: ?>
            <section class="account-card">
                <?php if ($props): ?>
                    <dl class="account-props">
                        <?php foreach ($props as $prop): ?>
                            <div>
                                <dt><?=htmlspecialcharsbx($prop['NAME'])?></dt>
                                <dd><?=htmlspecialcharsbx($prop['VALUE'])?></dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                <?php endif; ?>
                <?php if ($comment !== ''): ?>
                    <p class="account-comment"><span>Комментарий</span><?=htmlspecialcharsbx($comment)?></p>
                <?php endif; ?>
                <div class="account-table-wrap">
                    <table class="account-table">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Количество</th>
                                <?php if ($hasWeight): ?><th>Вес</th><?php endif; ?>
                                <th>Цена</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <?php
                                $qty = (float)$item['QUANTITY'];
                                $measure = trim((string)$item['MEASURE_NAME']);
                                $qtyText = $formatQty($qty) . ($measure !== '' ? ' ' . $measure : '');
                                $line = (float)$item['PRICE'] * $qty;
                                $weight = (float)$item['WEIGHT'];
                                $weightText = '';
                                if ($weight > 0) {
                                    $kg = $weight / 1000;
                                    $weightText = rtrim(rtrim(number_format($kg, 3, ',', ' '), '0'), ',') . ' кг';
                                }
                                ?>
                                <?php
                                $productUrl = $productUrls[(int)$item['PRODUCT_ID']] ?? '';
                                $productName = htmlspecialcharsbx((string)$item['NAME']);
                                ?>
                                <tr>
                                    <td><?php if ($productUrl !== ''): ?><a href="<?=htmlspecialcharsbx($productUrl)?>"><?=$productName?></a><?php else: ?><?=$productName?><?php endif; ?></td>
                                    <td><?=htmlspecialcharsbx($qtyText)?></td>
                                    <?php if ($hasWeight): ?><td><?=htmlspecialcharsbx($weightText !== '' ? $weightText : '—')?></td><?php endif; ?>
                                    <td><?=htmlspecialcharsbx($formatMoney((float)$item['PRICE']))?></td>
                                    <td><?=htmlspecialcharsbx($formatMoney($line))?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>
    </div>
</div>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
