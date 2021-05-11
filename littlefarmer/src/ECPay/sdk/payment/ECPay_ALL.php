<?php


namespace App\ECPay\sdk\payment;

class ECPay_ALL extends ECPay_Verification
{
    public  $arPayMentExtend = array();

    function filter_string($arExtend = array(),$InvoiceMark = ''){
        return $arExtend ;
    }
}