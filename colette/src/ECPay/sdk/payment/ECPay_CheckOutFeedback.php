<?php


namespace App\ECPay\sdk\payment;


class ECPay_CheckOutFeedback extends ECPay_Aio
{
    static function CheckOut($arParameters = array(),$HashKey = '' ,$HashIV = ''){
        // 變數宣告。
        $arErrors = array();
        $arFeedback = array();
        $szCheckMacValue = '';

        $EncryptType = $arParameters["EncryptType"];
        unset($arParameters["EncryptType"]);

        // 重新整理回傳參數。
        foreach ($arParameters as $keys => $value) {
            if ($keys != 'CheckMacValue') {
                if ($keys == 'PaymentType') {
                    $value = str_replace('_CVS', '', $value);
                    $value = str_replace('_BARCODE', '', $value);
                    $value = str_replace('_CreditCard', '', $value);
                }
                if ($keys == 'PeriodType') {
                    $value = str_replace('Y', 'Year', $value);
                    $value = str_replace('M', 'Month', $value);
                    $value = str_replace('D', 'Day', $value);
                }
                $arFeedback[$keys] = $value;
            }
        }

        $CheckMacValue = ECPay_CheckMacValue::generate($arParameters,$HashKey,$HashIV,$EncryptType);

        if ($CheckMacValue != $arParameters['CheckMacValue']) {
            array_push($arErrors, 'CheckMacValue verify fail.');
        }

        if (sizeof($arErrors) > 0) {
            throw new \Exception(join('- ', $arErrors));
        }

        return $arFeedback;
    }
}