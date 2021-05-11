<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 5:01 PM
 */

namespace App\ECPay\sdk\einvoice;

class ECPay_ALLOWANCE_VOID_SEARCH
{
    // 所需參數
    public $parameters = array(
        'TimeStamp'		=>'',
        'MerchantID'		=>'',
        'CheckMacValue'		=>'',
        'InvoiceNo'		=>'',
        'AllowanceNo'		=>''
    );

    // 需要做urlencode的參數
    public $urlencode_field = array(
        'Reason' 		=>''
    );

    // 不需要送壓碼的欄位
    public $none_verification = array(
        'Reason'		=>'',
        'CheckMacValue'		=>''
    );

    /**
     * 1寫入參數
     *
     * @param array $arParameters
     * @return array
     */
    function insert_string($arParameters = array())
    {
        foreach ($this->parameters as $key => $value) {
            if(isset($arParameters[$key])) {
                $this->parameters[$key] = $arParameters[$key];
            }
        }

        return $this->parameters ;
    }

    /**
     * 2-2 驗證參數格式
     *
     * @param array $arParameters
     * @return array
     * @throws \Exception
     */
    function check_extend_string($arParameters = array())
    {
        $arErrors = array();

        // 37.發票號碼 InvoiceNo
        // *必填項目
        if(strlen($arParameters['InvoiceNo']) == 0 ) {
            array_push($arErrors, '37:InvoiceNo is required.');
        }

        // *預設長度固定10碼
        if (strlen($arParameters['InvoiceNo']) != 10) {
            array_push($arErrors, '37:InvoiceNo length as 10.');
        }

        // 44.折讓編號 AllowanceNo
        // *必填項目
        if(strlen($arParameters['AllowanceNo']) == 0) {
            array_push($arErrors, "44:AllowanceNo is required.");
        }

        // *若有值長度固定16字元
        if(strlen($arParameters['AllowanceNo']) != 0 && strlen($arParameters['AllowanceNo']) != 16 ) {
            array_push($arErrors, '44:AllowanceNo length as 16.');
        }

        if(sizeof($arErrors)>0) {
            throw new \Exception(join('<br>', $arErrors));
        }

        return $arParameters ;
    }

    /**
     * 4欄位例外處理方式(送壓碼前)
     *
     * @param array $arParameters
     * @return array]
     */
    function check_exception($arParameters = array())
    {
        return $arParameters ;
    }
}
