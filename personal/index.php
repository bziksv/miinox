<?php
define('NEED_AUTH', true);
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

global $USER, $APPLICATION;

$APPLICATION->SetTitle('Личный кабинет');

$userId = (int)$USER->GetID();
$profile = CUser::GetByID($userId)->Fetch() ?: [];
$notice = '';
$error = '';

$normalizePhone = static function (string $raw): string {
    $digits = preg_replace('/\D+/', '', $raw);
    if ($digits === '') {
        return '';
    }
    if (strlen($digits) === 11 && $digits[0] === '8') {
        $digits = '7' . substr($digits, 1);
    }
    if (strlen($digits) === 10) {
        $digits = '7' . $digits;
    }
    if (strlen($digits) !== 11 || $digits[0] !== '7') {
        return '';
    }
    return '+' . $digits;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_bitrix_sessid() && isset($_POST['save_profile'])) {
    $phoneRaw = trim((string)($_POST['PERSONAL_PHONE'] ?? ''));
    $phone = $phoneRaw === '' ? '' : $normalizePhone($phoneRaw);
    if ($phoneRaw !== '' && $phone === '') {
        $error = 'Укажите телефон в формате +7XXXXXXXXXX';
    } else {
        $fields = [
            'NAME' => trim((string)($_POST['NAME'] ?? '')),
            'LAST_NAME' => trim((string)($_POST['LAST_NAME'] ?? '')),
            'EMAIL' => trim((string)($_POST['EMAIL'] ?? '')),
            'PERSONAL_PHONE' => $phone,
            'WORK_COMPANY' => trim((string)($_POST['WORK_COMPANY'] ?? '')),
        ];
        $user = new CUser();
        if ($fields['EMAIL'] === '') {
            $error = 'Укажите e-mail';
        } elseif (!$user->Update($userId, $fields)) {
            $error = strip_tags((string)$user->LAST_ERROR);
            if ($error === '') {
                $error = 'Не удалось сохранить профиль';
            }
        } else {
            $notice = 'Профиль сохранён';
            $profile = CUser::GetByID($userId)->Fetch() ?: $profile;
        }
    }
}

$orders = [];
$statuses = [];
if (CModule::IncludeModule('sale')) {
    $statusRes = CSaleStatus::GetList(['SORT' => 'ASC'], ['LID' => LANGUAGE_ID]);
    while ($status = $statusRes->Fetch()) {
        $statuses[(string)$status['ID']] = (string)$status['NAME'];
    }
    $orderRes = CSaleOrder::GetList(
        ['DATE_INSERT' => 'DESC'],
        ['USER_ID' => $userId],
        false,
        ['nTopCount' => 15],
        ['ID', 'ACCOUNT_NUMBER', 'DATE_INSERT', 'STATUS_ID', 'PRICE', 'CURRENCY', 'PAYED', 'CANCELED']
    );
    while ($order = $orderRes->Fetch()) {
        $orders[] = $order;
    }
}

$fullName = trim((string)($profile['NAME'] ?? '') . ' ' . (string)($profile['LAST_NAME'] ?? ''));
$email = (string)($profile['EMAIL'] ?? '');
$logoutUrl = $APPLICATION->GetCurPageParam('logout=yes&' . bitrix_sessid_get(), ['logout', 'login', 'sessid']);
?>
<div class="account-page">
    <div class="account-page__inner">
        <header class="account-head">
            <div>
                <h1>Личный кабинет</h1>
                <p><?=htmlspecialcharsbx($fullName !== '' ? $fullName : $email)?></p>
            </div>
            <a class="account-logout" href="<?=htmlspecialcharsbx($logoutUrl)?>">Выйти</a>
        </header>

        <?php if ($notice !== ''): ?>
            <p class="account-note account-note--ok"><?=htmlspecialcharsbx($notice)?></p>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
            <p class="account-note account-note--err"><?=htmlspecialcharsbx($error)?></p>
        <?php endif; ?>

        <div class="account-grid">
            <section class="account-card">
                <h2>Профиль</h2>
                <form method="post" action="/personal/">
                    <?=bitrix_sessid_post()?>
                    <label class="account-field">
                        <span>Имя</span>
                        <input type="text" name="NAME" maxlength="50" value="<?=htmlspecialcharsbx((string)($profile['NAME'] ?? ''))?>">
                    </label>
                    <label class="account-field">
                        <span>Фамилия</span>
                        <input type="text" name="LAST_NAME" maxlength="50" value="<?=htmlspecialcharsbx((string)($profile['LAST_NAME'] ?? ''))?>">
                    </label>
                    <label class="account-field">
                        <span>E-mail</span>
                        <input type="email" name="EMAIL" maxlength="255" required value="<?=htmlspecialcharsbx($email)?>">
                    </label>
                    <label class="account-field">
                        <span>Телефон</span>
                        <input type="tel" name="PERSONAL_PHONE" maxlength="20" inputmode="tel" placeholder="+7 900 000-00-00" value="<?=htmlspecialcharsbx((string)($profile['PERSONAL_PHONE'] ?? ''))?>">
                    </label>
                    <label class="account-field">
                        <span>Компания</span>
                        <input type="text" name="WORK_COMPANY" maxlength="255" value="<?=htmlspecialcharsbx((string)($profile['WORK_COMPANY'] ?? ''))?>">
                    </label>
                    <button type="submit" name="save_profile" value="Y">Сохранить</button>
                </form>
            </section>

            <section class="account-card">
                <div class="account-card__head">
                    <h2>Заказы</h2>
                    <a href="/catalog/">В каталог</a>
                </div>
                <?php if (!$orders): ?>
                    <p class="account-empty">Заказов пока нет. Соберите позиции в каталоге и оформите заказ.</p>
                <?php else: ?>
                    <div class="account-table-wrap">
                        <table class="account-table">
                            <thead>
                                <tr>
                                    <th>Номер</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <?php
                                    $statusId = (string)$order['STATUS_ID'];
                                    $statusName = $statuses[$statusId] ?? $statusId;
                                    $date = (string)$order['DATE_INSERT'];
                                    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $date, $match)) {
                                        $date = $match[3] . '.' . $match[2] . '.' . $match[1];
                                    } elseif (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})/', $date, $match)) {
                                        $date = $match[1] . '.' . $match[2] . '.' . $match[3];
                                    }
                                    $canceled = (string)$order['CANCELED'] === 'Y';
                                    ?>
                                    <tr>
                                        <td><a href="/personal/order/<?=(int)$order['ID']?>/"><?=htmlspecialcharsbx((string)($order['ACCOUNT_NUMBER'] ?: $order['ID']))?></a></td>
                                        <td><?=htmlspecialcharsbx($date)?></td>
                                        <td><span class="account-status<?=$canceled ? ' is-canceled' : ''?>"><?=htmlspecialcharsbx($canceled ? 'Отменён' : $statusName)?></span></td>
                                        <td><?=htmlspecialcharsbx(number_format((float)$order['PRICE'], 0, ',', ' ') . ' ₽')?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>
<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';
