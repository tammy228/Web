<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:58 PM
 */

namespace App\ECPay\sdk\einvoice;

class ECPay_INVOICE_SEARCH
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
        'IIS_Customer_Name' 	=>'',
        'IIS_Customer_Addr' 	=>'',
        'ItemName' 		=>'',
        'ItemWord' 		=>'',
        'ItemRemark'		=>'',
        'InvoiceRemark'		=>''
    );

    // 不需要送壓碼的欄位
    public $none_verification = array(
        'ItemName' 		=>'',
        'ItemWord' 		=>'',
        'ItemRemark'		=>'',
        'InvoiceRemark'		=>'',
        'PosBarCode' 		=>'',
        'QRCode_Left' 		=>'',
        'QRCode_Right' 		=>'',
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
        if(isset($arParameters['IIS_Customer_Email'])) {
            $arParameters['IIS_Customer_Email'] = str_replace('+',' ',$arParameters['IIS_Customer_Email']);
        }

        if(isset($arParameters['IIS_Carruer_Num'])) {
            $arParameters['IIS_Carruer_Num'] = str_replace('+',' ',$arParameters['IIS_Carruer_Num']);
        }

        return $arParameters ;
    }
}