<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:46 PM
 */

namespace App\ECPay\sdk\einvoice;

class EcpayInvoice
{
    /**
     * 版本
     */
    const VERSION = '1.0.2002102';

    public $TimeStamp 	= '';
    public $MerchantID 	= '';
    public $HashKey 	= '';
    public $HashIV 		= '';
    public $Send 		= 'Send';
    public $Invoice_Method 	= 'INVOICE';		// 電子發票執行項目
    public $Invoice_Url 	= 'Invoice_Url';	// 電子發票執行網址

    function __construct()
    {
        $this->Send = array(
            'RelateNumber' => '',
            'CustomerID' => '',
            'CustomerIdentifier' => '',
            'CustomerName' => '',
            'CustomerAddr' => '',
            'CustomerPhone' => '',
            'CustomerEmail' => '',
            'ClearanceMark' => '',
            'Print' => EcpayPrintMark::No,
            'Donation' => EcpayDonation::No,
            'LoveCode' => '',
            'CarruerType' => EcpayCarruerType::None,
            'CarruerNum' => '',
            'TaxType' => '',
            'SalesAmount' => '',
            'InvoiceRemark' => '',
            'Items' => array(),
            'InvType' => '',
            'vat' => EcpayVatType::Yes,
            'DelayFlag' => '',
            'DelayDay' => 0,
            'Tsr' => '',
            'PayType' => '',
            'PayAct' => '',
            'NotifyURL' => '',
            'InvoiceNo' => '',
            'AllowanceNotify' => '',
            'NotifyMail' => '',
            'NotifyPhone' => '',
            'AllowanceAmount' => '',
            'InvoiceNumber'  => '',
            'Reason'  => '',
            'AllowanceNo' => '',
            'Phone' => '',
            'Notify' => '',
            'InvoiceTag' => '',
            'Notified' => '',
            'BarCode' => '',
            'OnLine' => true
        );

        $this->TimeStamp = time();
    }

    /**
     * @return array
     * @throws \Exception
     */
    function Check_Out()
    {
        $arParameters = array_merge( array('MerchantID' => $this->MerchantID) , array('TimeStamp' => $this->TimeStamp), $this->Send);
        return ECPay_Invoice_Send::CheckOut($arParameters, $this->HashKey, $this->HashIV, $this->Invoice_Method, $this->Invoice_Url);
    }

    /**
     * 取得線上折讓單回傳資料
     *
     * @param  array $merchantInfo
     * @param  array $parameters
     * @return array
     *
     * @throws \Exception
     */
    function allowanceByCollegiateResponse($merchantInfo, $parameters)
    {
        $merchantInfo['method'] = EcpayInvoiceMethod::ALLOWANCE_BY_COLLEGIATE ;
        return ecpayResponse::response($merchantInfo, $parameters);
    }
}