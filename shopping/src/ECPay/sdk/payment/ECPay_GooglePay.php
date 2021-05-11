<?php


namespace App\ECPay\sdk\payment;

class ECPay_GooglePay extends ECPay_Verification
{
    public $arPayMentExtend = array();

    function filter_string($arExtend = array(), $InvoiceMark = ''){
        $arExtend = parent::filter_string($arExtend, $InvoiceMark);
        return $arExtend ;
    }
}