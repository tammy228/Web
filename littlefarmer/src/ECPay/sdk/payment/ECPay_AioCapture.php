<?php

namespace App\ECPay\sdk\payment;

class ECPay_AioCapture extends ECPay_Aio
{
    /**
     * @param array $arParameters
     * @param string $HashKey
     * @param string $HashIV
     * @param string $ServiceURL
     * @return array
     * @throws \Exception
     */
    static function Capture($arParameters=array(),$HashKey='',$HashIV='',$ServiceURL=''){

        $arErrors   = array();
        $arFeedback = array();

        $EncryptType = $arParameters["EncryptType"];
        unset($arParameters["EncryptType"]);

        $szCheckMacValue = ECPay_CheckMacValue::generate($arParameters,$HashKey,$HashIV,$EncryptType);
        $arParameters["CheckMacValue"] = $szCheckMacValue;

        // 送出查詢並取回結果。
        $szResult = ECPay_Aio::ServerPost($arParameters,$ServiceURL);

        // 轉結果為陣列。
        parse_str($szResult, $arResult);

        // 重新整理回傳參數。
        foreach ($arResult as $keys => $value) {
            $arFeedback[$keys] = $value;
        }

        if (sizeof($arErrors) > 0) {
            throw new \Exception(join('- ', $arErrors));
        }

        return $arFeedback;

    }
}