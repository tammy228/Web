<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:59 PM
 */

namespace App\ECPay\sdk\einvoice;

class ECPay_INVOICE_VOID_SEARCH
{
    // 所需參數
    public $parameters = array(
        'TimeStamp'		=>'',
        'MerchantID'		=>'',
        'RelateNumber'		=>'',
        'CheckMacValue'		=>''
    );

    // 需要做urlencode的參數
    public $urlencode_field = array(
        'Reason' 		=>''
    );

    // 不需要送壓碼的欄位
    public $none_verification = array(
        'Reason' 		=>'',
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

        // 4.廠商自訂編號

        // *預設不可為空值
        if(strlen($arParameters['RelateNumber']) == 0) {
            array_push($arErrors, '4:RelateNumber is required.');
        }

        // *預設最大長度為30碼
        if(strlen($arParameters['RelateNumber']) > 30) {
            array_push($arErrors, '4:RelateNumber max langth as 30.');
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
     * @return array
     */
    function check_exception($arParameters = array())
    {
        return $arParameters ;
    }
}