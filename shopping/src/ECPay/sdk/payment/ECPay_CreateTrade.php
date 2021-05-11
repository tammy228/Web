<?php


namespace App\ECPay\sdk\payment;


class ECPay_CreateTrade extends ECPay_Aio
{

    /**
     * 付款方式物件
     *
     * @var ECPay_Verification $PaymentObj
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
        $PaymentMethod    = 'ECPay_'.$arParameters['ChoosePayment'];
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
     * @param array $arParameters
     * @param array $arExtend
     * @param string $HashKey
     * @param string $HashIV
     * @param string $ServiceURL
     * @return array
     * @throws \Exception
     */
    static function CheckOut($arParameters = array(),$arExtend = array(),$HashKey='',$HashIV='',$ServiceURL=''){

        $arErrors   = array();
        $arFeedback = array();
        $szCheckMacValueReturn = '' ;

        $arParameters = self::process($arParameters,$arExtend);

        //產生檢查碼
        $szCheckMacValue = ECPay_CheckMacValue::generate($arParameters,$HashKey,$HashIV,$arParameters['EncryptType']);
        $arParameters["CheckMacValue"] = $szCheckMacValue;

        // 送出查詢並取回結果。
        $szResult = ECPay_Aio::ServerPost($arParameters,$ServiceURL);

        // 轉結果為陣列。
        $arResult = json_decode($szResult,true);

        // 重新整理回傳參數。
        foreach ($arResult as $keys => $value) {
            if ($keys == 'CheckMacValue') {
                $szCheckMacValueReturn = $value;
            } else {
                $arFeedback[$keys] = $value;
            }
        }

        if (array_key_exists('RtnCode', $arFeedback) && $arFeedback['RtnCode'] != '1') {
            array_push($arErrors, vsprintf('#%s: %s', array($arFeedback['RtnCode'], $arFeedback['RtnMsg'])));
        }
        else{
            // 參數取回壓碼驗證
            $szCheckMacValueReturnParameters = ECPay_CheckMacValue::generate($arFeedback,$HashKey,$HashIV,$arParameters['EncryptType']);

            if($szCheckMacValueReturnParameters != $szCheckMacValueReturn){
                array_push($arErrors, 'CheckMacValue verify fail.');
            }
        }

        if (sizeof($arErrors) > 0) {
            throw new \Exception(join('- ', $arErrors));
        }

        return $arFeedback ;
    }
}