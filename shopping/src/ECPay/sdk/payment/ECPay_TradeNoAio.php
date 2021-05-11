<?php


namespace App\ECPay\sdk\payment;

class ECPay_TradeNoAio extends ECPay_Aio
{
    static function CheckOut($target = "_self",$arParameters = array(),$HashKey='',$HashIV='',$ServiceURL=''){
        //產生檢查碼
        $EncryptType = $arParameters['EncryptType'];
        unset($arParameters['EncryptType']);

        $szCheckMacValue = ECPay_CheckMacValue::generate($arParameters,$HashKey,$HashIV,$EncryptType);

        //生成表單，自動送出
        $szHtml = parent::HtmlEncode($target, $arParameters, $ServiceURL, $szCheckMacValue, '') ;
        return $szHtml;
    }
}