<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/php_interface/include/functions.php";

function priceDiscount($id){
    global $USER;
    $ar_res_price = CCatalogProduct::GetOptimalPrice($id, 1, $USER->GetUserGroupArray(), 'N');
    if($ar_res_price['DISCOUNT_PRICE'])
        return $ar_res_price;
    else
        return false;

}

function getGroupPriceForProduct($PriceID, $productID){
    if(empty($PriceID) || empty($productID))
        return false;

    $db_res = CPrice::GetList(array(), array("PRODUCT_ID" => $productID, "CATALOG_GROUP_ID" => $PriceID));
    if ($ar_res = $db_res->Fetch())
        return CurrencyFormat($ar_res["PRICE"], $ar_res["CURRENCY"]);
}

function isDebug(){
    $config = \Bitrix\Main\Config\Configuration::getInstance();
    $configConnections = $config->get("exception_handling");
    if($configConnections['debug'])
        return false;

    return true;
}


AddEventHandler("sale", "OnOrderNewSendEmail", "bxModifySaleMails");
function bxModifySaleMails($orderID, &$eventName, &$arFields)
{
    $order_props = CSaleOrderPropsValue::GetOrderProps($orderID);
    $phone = "";
    $email = "";
    $address = "";

    while ($arProps = $order_props->Fetch())
    {
        if ($arProps["CODE"] == "PHONE")
            $phone = htmlspecialchars($arProps["VALUE"]);

        if ($arProps["CODE"] == "EMAIL")
            $email = htmlspecialchars($arProps["VALUE"]);

        if ($arProps["CODE"] == "ADDRESS")
            $address = htmlspecialchars($arProps["VALUE"]);
    }

    $arFields["PHONE"] = $phone;
    $arFields["EMAIL"] = $email;
    $arFields["ADDRESS"] = $address;

    $comment = (CSaleOrder::GetByID($orderID)['USER_DESCRIPTION']) ?: null;
    if($comment)
        $arFields["COMMENT"] = 'Ваш комментарий: '.$comment;

    if($_COOKIE['roistat_visit'])
        $arFields["ROI_VISIT"] = $_COOKIE['roistat_visit'];
}

AddEventHandler('main', 'OnBeforeUserRegister', 'miinoxRequireRegisterPhone');
function miinoxRequireRegisterPhone(&$fields)
{
    $digits = preg_replace('/\D+/', '', (string)($fields['PHONE_NUMBER'] ?? ''));
    if (strlen($digits) === 11 && $digits[0] === '8') {
        $digits = '7' . substr($digits, 1);
    }
    if (strlen($digits) === 10) {
        $digits = '7' . $digits;
    }
    if (strlen($digits) !== 11 || $digits[0] !== '7') {
        $GLOBALS['APPLICATION']->ThrowException('Укажите телефон в формате +7XXXXXXXXXX');
        return false;
    }

    $phone = '+' . $digits;
    $fields['PHONE_NUMBER'] = $phone;
    $fields['PERSONAL_PHONE'] = $phone;
    return true;
}
