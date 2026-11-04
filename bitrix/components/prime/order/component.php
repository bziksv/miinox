<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\Loader,
    Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
	Bitrix\Main,
	Bitrix\Iblock,
    Bitrix\Sale;

global $USER;

Bitrix\Main\Loader::includeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");

$arParams['PERSON_TYPE'] = ($arParams['PERSON_TYPE']) ? $arParams['PERSON_TYPE'] : "1";

$arResult = [];
$arResult["ERROR_MESSAGE"] = [];

if($arParams['PERSON_TYPE']){
    $db_props = CSaleOrderProps::GetList(
        array("SORT" => "ASC"),
        array(
            "PERSON_TYPE_ID" => $arParams['PERSON_TYPE']
        ),
        false,
        false,
        array()
    );
    while ($props = $db_props->Fetch())
    {
        $arResult['FIELD'][] = $props;
    }
}

$prefill = [];
if (is_object($USER) && $USER->IsAuthorized()) {
    $profile = CUser::GetByID((int)$USER->GetID())->Fetch() ?: [];
    $fullName = trim(implode(' ', array_filter([
        trim((string)($profile['LAST_NAME'] ?? '')),
        trim((string)($profile['NAME'] ?? '')),
        trim((string)($profile['SECOND_NAME'] ?? '')),
    ])));
    $phone = '';
    foreach (['PERSONAL_PHONE', 'PERSONAL_MOBILE', 'WORK_PHONE'] as $phoneField) {
        $phone = trim((string)($profile[$phoneField] ?? ''));
        if ($phone !== '') {
            break;
        }
    }
    $address = trim(implode(', ', array_filter([
        trim((string)($profile['PERSONAL_CITY'] ?? '')),
        trim((string)($profile['PERSONAL_STREET'] ?? '')),
    ])));
    foreach ($arResult['FIELD'] as $field) {
        $code = (string)$field['CODE'];
        $value = '';
        if (($field['IS_EMAIL'] ?? '') === 'Y') {
            $value = trim((string)($profile['EMAIL'] ?? ''));
        } elseif (($field['IS_PHONE'] ?? '') === 'Y') {
            $value = $phone;
        } elseif ($code === 'COMPANY') {
            $value = trim((string)($profile['WORK_COMPANY'] ?? ''));
        } elseif (($field['IS_PAYER'] ?? '') === 'Y' || ($field['IS_PROFILE_NAME'] ?? '') === 'Y') {
            $value = $fullName;
        } elseif (($field['IS_ADDRESS'] ?? '') === 'Y') {
            $value = $address;
        } elseif ($code === 'CITY') {
            $value = trim((string)($profile['PERSONAL_CITY'] ?? ''));
        } elseif ($code === 'ZIP') {
            $value = trim((string)($profile['PERSONAL_ZIP'] ?? ''));
        }
        if ($value !== '') {
            $prefill[$code] = $value;
        }
    }

    $lastOrder = CSaleOrder::GetList(
        ['ID' => 'DESC'],
        ['USER_ID' => (int)$USER->GetID(), 'LID' => SITE_ID],
        false,
        ['nTopCount' => 1],
        ['ID']
    )->Fetch();
    if ($lastOrder) {
        $propRes = CSaleOrderPropsValue::GetList([], ['ORDER_ID' => (int)$lastOrder['ID']]);
        while ($prop = $propRes->Fetch()) {
            $code = (string)$prop['CODE'];
            $value = trim((string)$prop['VALUE']);
            if ($value !== '' && empty($prefill[$code])) {
                $prefill[$code] = $value;
            }
        }
    }
}

if (!empty($arResult['FIELD'])) {
    $posted = check_bitrix_sessid() && $_SERVER['REQUEST_METHOD'] === 'POST';
    foreach ($arResult['FIELD'] as &$field) {
        $code = (string)$field['CODE'];
        if ($posted && isset($_REQUEST[$code]) && is_string($_REQUEST[$code])) {
            $field['VALUE'] = $_REQUEST[$code];
        } else {
            $field['VALUE'] = (string)($prefill[$code] ?? '');
        }
    }
    unset($field);
}

if(check_bitrix_sessid() && $_SERVER["REQUEST_METHOD"] == "POST")
{
    $request = Context::getCurrent()->getRequest();

    foreach ($arResult['FIELD'] as $field){
        if($field['REQUIED'] == 'Y'){
            if(empty($request[$field['CODE']]))
                $arResult["ERROR_MESSAGE"][] = "Пустое поле: $field[NAME]";
        }
    }

    if ((string)$request['personal_consent'] !== 'Y') {
        $arResult["ERROR_MESSAGE"][] = 'Нужно согласие на обработку персональных данных';
    }

    if(empty($arResult["ERROR_MESSAGE"]))
    {

        $siteId = Context::getCurrent()->getSite();

        $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteId);
        if(count($basket) > 0){

            $currencyCode = CurrencyManager::getBaseCurrency();
            $order = Sale\Order::create($siteId, $USER->isAuthorized() ? $USER->GetID() : 1);
            $order->setPersonTypeId($arParams['PERSON_TYPE']);
            $order->setField('CURRENCY', $currencyCode);

            if($request['COMMENT'])
                $order->setField('USER_DESCRIPTION', $request['COMMENT']);

            $propertyCollection = $order->getPropertyCollection();
            foreach($propertyCollection as $prop){
                $prop->setValue($request[$prop->getField('CODE')]);
            }

            $order->setBasket($basket);

            $order->doFinalAction(true);
            $result = $order->save();
            $arResult['ORDER_ID'] = $order->getId();
        }
    }
}

if(strlen($arResult['ORDER_ID']) > 0)
    $this->setTemplateName('successful');

$this->includeComponentTemplate();


