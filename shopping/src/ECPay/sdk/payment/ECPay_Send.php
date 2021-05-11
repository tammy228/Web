<?php

namespace App\ECPay\sdk\payment;

class ECPay_Send extends ECPay_Aio
{
    /**
     * 付款方式物件
     *
     * @var ECPay_ALL $PaymentObj
     */
    public static $PaymentObj ;

    /**
     * @param array $arParameters
     * @param array $arExtend
     * @return array
     * @throws \Exception
     */
    protected static function process($arParameters = array(),$arExtend = array())
    {
        //宣告付款方式物件
        $PaymentMethod    = 'App\ECPay\sdk\payment\ECPay_'.$arParameters['ChoosePayment'];
        self::$PaymentObj = new $PaymentMethod;

        //檢查參數
        $arParameters = self::$PaymentObj->check_string($arParameters);

        //檢查商品
        $arParameters = self::$PaymentObj->check_goods($arParameters);

        //檢查各付款方式的額外參數&電子發票參數
        $arExtend = self::$PaymentObj->check_extend_string($arExtend,$arParameters['InvoiceMark']);

        //過濾
        $arExtend = self::$PaymentObj->filter_string($arExtend,$arParameters['InvoiceMark']);

        //合併共同參數及延伸參數
        return array_merge($arParameters,$arExtend) ;
    }


    /**
     * @param string $target
     * @param array $arParameters
     * @param array $arExtend
     * @param string $HashKey
     * @param string $HashIV
     * @param string $ServiceURL
     * @throws \Exception
     */
    static function CheckOut($target = "_self",$arParameters = array(),$arExtend = array(),$HashKey='',$HashIV='',$ServiceURL=''){

        $arParameters = self::process($arParameters,$arExtend);
        //產生檢查碼
        $szCheckMacValue = ECPay_CheckMacValue::generate($arParameters,$HashKey,$HashIV,$arParameters['EncryptType']);

        //生成表單，自動送出
        $szHtml = parent::HtmlEncode($target, $arParameters, $ServiceURL, $szCheckMacValue, '') ;
        return $szHtml;
    }

    /**
     * @param string $paymentButton
     * @param string $target
     * @param array $arParameters
     * @param array $arExtend
     * @param string $HashKey
     * @param string $HashIV
     * @param string $ServiceURL
     * @return string
     * @throws \Exception
     */
    static function CheckOutString($paymentButton = 'Submit',$target = "_self",$arParameters = array(),$arExtend = array(),$HashKey='',$HashIV='',$ServiceURL=''){

        $arParameters = self::process($arParameters,$arExtend);
        //產生檢查碼
        $szCheckMacValue = ECPay_CheckMacValue::generate($arParameters,$HashKey,$HashIV,$arParameters['EncryptType']);

        //生成表單
        return parent::HtmlEncode($target, $arParameters, $ServiceURL, $szCheckMacValue, $paymentButton);
    }
}
