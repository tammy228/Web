<?php


namespace App\ECPay\sdk\payment;

class ECPay_Credit extends ECPay_Verification
{
    public $arPayMentExtend = array(
        "CreditInstallment" => '',
        "InstallmentAmount" => 0,
        "Redeem"            => FALSE,
        "UnionPay"          => FALSE,
        "Language"          => '',
        "BindingCard"       => '',
        "MerchantMemberID"  => '',
        "PeriodAmount"      => '',
        "PeriodType"        => '',
        "Frequency"         => '',
        "ExecTimes"         => '',
        "PeriodReturnURL"   => ''
    );

    function filter_string($arExtend = array(),$InvoiceMark = ''){
        $arExtend = parent::filter_string($arExtend, $InvoiceMark);
        return $arExtend ;
    }
}