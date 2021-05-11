<?php

namespace App\ECPay\sdk\payment;

class ECPay_DoAction extends ECPay_Aio
{
    /**
     * @param array $arParameters
     * @param string $HashKey
     * @param string $HashIV
     * @param string $ServiceURL
     * @return array
     * @throws \Exception
     */
    static function CheckOut($arParameters = array(),$HashKey ='',$HashIV ='',$ServiceURL = ''){
        // 變數宣告。
        $arErrors = array();
        $arFeedback = array();

        $EncryptType = $arParameters["EncryptType"];
        unset($arParameters["EncryptType"]);

        //產生驗證碼
        $szCheckMacValue = ECPay_CheckMacValue::generate($arParameters,$HashKey,$HashIV,$EncryptType);
        $arParameters["CheckMacValue"] = $szCheckMacValue;
        // 送出查詢並取回結果。
        $szResult = ECPay_Aio::ServerPost($arParameters,$ServiceURL);
        // 轉結果為陣列。
        parse_str($szResult, $arResult);
        // 重新整理回傳參數。
        foreach ($arResult as $keys => $value) {
            if ($keys == 'CheckMacValue') {
                $szCheckMacValue = $value;
            } else {
                $arFeedback[$keys] = $value;
            }
        }

        if (array_key_exists('RtnCode', $arFeedback) && $arFeedback['RtnCode'] != '1') {
            array_push($arErrors, vsprintf('#%s: %s', array($arFeedback['RtnCode'], $arFeedback['RtnMsg'])));
        }

        if (sizeof($arErrors) > 0) {
            throw new \Exception(join('- ', $arErrors));
        }

        return $arFeedback ;

    }
}