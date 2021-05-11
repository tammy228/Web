<?php


namespace App\ECPay\sdk\payment;


class ECPay_QueryTrade extends ECPay_Aio
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
        $arErrors = array();
        $arFeedback = array();
        $arConfirmArgs = array();

        $EncryptType = $arParameters["EncryptType"];
        unset($arParameters["EncryptType"]);

        // 呼叫查詢。
        if (sizeof($arErrors) == 0) {
            $arParameters["CheckMacValue"] = ECPay_CheckMacValue::generate($arParameters,$HashKey,$HashIV,$EncryptType);
            // 送出查詢並取回結果。
            $szResult = ECPay_Aio::ServerPost($arParameters,$ServiceURL);

            // 轉結果為陣列。
            $arResult = json_decode($szResult,true);

            // 重新整理回傳參數。
            foreach ($arResult as $keys => $value) {
                $arFeedback[$keys] = $value;
            }
        }

        if (sizeof($arErrors) > 0) {
            throw new \Exception(join('- ', $arErrors));
        }

        return $arFeedback ;
    }
}