<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 5:02 PM
 */

namespace App\ECPay\sdk\einvoice;

class ECPay_CHECK_MOBILE_BARCODE
{
    // 所需參數
    public $parameters = array(
        'TimeStamp'		=>'',
        'MerchantID'		=>'',
        'CheckMacValue'		=>'',
        'BarCode'		=>''
    );

    // 需要做urlencode的參數
    public $urlencode_field = array(
    );

    // 不需要送壓碼的欄位
    public $none_verification = array(
        'CheckMacValue'		=>''
    );

    /**
     * 1寫入參數
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

        // 50.BarCode 手機條碼
        // *僅能為8碼且為必填
        if( strlen($arParameters['BarCode']) != 8 ) {
            array_push($arErrors, "50:BarCode max length as 8.");
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
        if(isset($arParameters['BarCode'])) {
            // 手機條碼 內包含+號則改為空白
            $arParameters['BarCode'] = str_replace('+',' ',$arParameters['BarCode']);
        }

        return $arParameters ;
    }
}