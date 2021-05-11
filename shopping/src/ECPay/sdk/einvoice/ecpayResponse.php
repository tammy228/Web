<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:49 PM
 */

namespace App\ECPay\sdk\einvoice;

class ecpayResponse
{
    // 發票物件
    public static $objReturn ;

    /**
     * 取得 Response 資料
     *
     * @param  array $merchantInfo
     * @param  array $parameters
     * @return array
     * @throws \Exception
     */
    static function response($merchantInfo = [], $parameters = [])
    {
        $invoiceMethod = 'ECPay_'.$merchantInfo['method'];
        self::$objReturn = new $invoiceMethod;

        // 壓碼檢查
        $parametersTmp = $parameters ;
        unset($parametersTmp['CheckMacValue']);
        $checkMacValue = ECPay_Invoice_CheckMacValue::generate($parametersTmp, $merchantInfo['hashKey'], $merchantInfo['hashIv']);

        if($checkMacValue != $parameters['CheckMacValue']){
            throw new \Exception('注意：壓碼錯誤');
        }

        return $parameters ;
    }
}