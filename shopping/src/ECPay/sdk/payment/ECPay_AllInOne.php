<?php


namespace App\ECPay\sdk\payment;

/**
 * AllInOne short summary.
 *
 * AllInOne description.
 *
 * 1.1.20221    *支援站內付全方位金流
 * 1.1.180313   *修正信用卡記憶卡號參數.
 * 1.1.190328   *設定class_exists的autoload參數為false.
 * 1.1.190917   *ServerPost改static調用
 * 1.1.1910310  *修正電子發票延伸檢查碼錯誤
 *
 * @version 1.1.1910310
 * @author charlie & wesley
 */
class ECPay_AllInOne {

    /**
     * @ SDK版本
     */
    const VERSION = '1.1.1910310';

    public $ServiceURL = 'ServiceURL';
    public $ServiceMethod = 'ServiceMethod';
    public $HashKey = 'HashKey';
    public $HashIV = 'HashIV';
    public $MerchantID = 'MerchantID';
    public $PaymentType = 'PaymentType';
    public $Send = 'Send';
    public $SendExtend = 'SendExtend';
    public $Query = 'Query';
    public $Action = 'Action';
    public $EncryptType = ECPay_EncryptType::ENC_MD5;

    function __construct() {

        $this->PaymentType = 'aio';
        $this->Send = array(
            "ReturnURL"         => '',
            "ClientBackURL"     => '',
            "OrderResultURL"    => '',
            "MerchantTradeNo"   => '',
            "MerchantTradeDate" => '',
            "PaymentType"       => 'aio',
            "TotalAmount"       => '',
            "TradeDesc"         => '',
            "ChoosePayment"     => ECPay_PaymentMethod::ALL,
            "Remark"            => '',
            "ChooseSubPayment"  => ECPay_PaymentMethodItem::None,
            "NeedExtraPaidInfo" => ECPay_ExtraPaymentInfo::No,
            "DeviceSource"      => '',
            "IgnorePayment"     => '',
            "PlatformID"        => '',
            "InvoiceMark"       => ECPay_InvoiceState::No,
            "Items"             => array(),
            "StoreID"           => '',
            "CustomField1"      => '',
            "CustomField2"      => '',
            "CustomField3"      => '',
            "CustomField4"      => '',
            'HoldTradeAMT'      => 0
        );

        $this->SendExtend = array();

        $this->Query = array(
            'MerchantTradeNo' => '',
            'TimeStamp' => ''
        );
        $this->Action = array(
            'MerchantTradeNo' => '',
            'TradeNo' => '',
            'Action' => ECPay_ActionType::C,
            'TotalAmount' => 0
        );
        $this->Capture = array(
            'MerchantTradeNo' => '',
            'CaptureAMT' => 0,
            'UserRefundAMT' => 0,
            'PlatformID' => ''
        );

        $this->TradeNo = array(
            'DateType' => '',
            'BeginDate' => '',
            'EndDate' => '',
            'MediaFormated' => ''
        );

        $this->Trade = array(
            'CreditRefundId' => '',
            'CreditAmount' => '',
            'CreditCheckCode' => ''
        );

        $this->Funding = array(
            "PayDateType" => '',
            "StartDate" => '',
            "EndDate" => ''
        );

    }

    /**
     * 產生訂單
     *
     * @param string $target
     * @throws \Exception
     * @return string
     */
    function CheckOut($target = "_self") {
        $arParameters = array_merge( array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType) ,$this->Send);
        return ECPay_Send::CheckOut($target,$arParameters,$this->SendExtend,$this->HashKey,$this->HashIV,$this->ServiceURL);
    }

    /**
     * 產生訂單html code
     *
     * @param string $paymentButton
     * @param string $target
     * @return string
     * @throws \Exception
     */
    function CheckOutString($paymentButton = 'Submit', $target = "_self") {
        $arParameters = array_merge( array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType) ,$this->Send);
        return ECPay_Send::CheckOutString($paymentButton,$target = "_self",$arParameters,$this->SendExtend,$this->HashKey,$this->HashIV,$this->ServiceURL);
    }

    /**
     * 取得付款結果通知的方法
     *
     * @return array
     * @throws \Exception
     */
    function CheckOutFeedback() {
        return $arFeedback = ECPay_CheckOutFeedback::CheckOut(
            array_merge($_POST, array('EncryptType' => $this->EncryptType)),
            $this->HashKey,
            $this->HashIV);
    }

    /**
     * 訂單查詢作業
     *
     * @return array
     * @throws \Exception
     */
    function QueryTradeInfo() {
        return $arFeedback = ECPay_QueryTradeInfo::CheckOut(array_merge($this->Query,array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType)) ,$this->HashKey ,$this->HashIV ,$this->ServiceURL) ;
    }

    /**
     * 信用卡定期定額訂單查詢的方法
     *
     * @return array
     * @throws \Exception
     */
    function QueryPeriodCreditCardTradeInfo() {
        return $arFeedback = ECPay_QueryPeriodCreditCardTradeInfo::CheckOut(array_merge($this->Query,array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType)) ,$this->HashKey ,$this->HashIV ,$this->ServiceURL);
    }

    /**
     * 信用卡關帳/退刷/取消/放棄的方法
     *
     * @return array
     * @throws \Exception
     */
    function DoAction() {
        return $arFeedback = ECPay_DoAction::CheckOut(
            array_merge($this->Action, array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType)),
            $this->HashKey,
            $this->HashIV,
            $this->ServiceURL);
    }

    /**
     * 合作特店申請撥款
     *
     * @return array
     * @throws \Exception
     */
    function AioCapture(){
        return $arFeedback = ECPay_AioCapture::Capture(
            array_merge($this->Capture,array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType)),
            $this->HashKey,
            $this->HashIV,
            $this->ServiceURL);
    }

    /**
     * 下載會員對帳媒體檔
     *
     * @param string $target
     */
    function TradeNoAio($target = "_self"){
        $arParameters = array_merge( array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType) ,$this->TradeNo);
        ECPay_TradeNoAio::CheckOut($target,$arParameters,$this->HashKey,$this->HashIV,$this->ServiceURL);
    }

    /**
     * 查詢信用卡單筆明細紀錄
     *
     * @return array
     * @throws \Exception
     */
    function QueryTrade(){
        return $arFeedback = ECPay_QueryTrade::CheckOut(array_merge($this->Trade,array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType)) ,$this->HashKey ,$this->HashIV ,$this->ServiceURL);
    }

    /**
     * @param string $target
     *
     * 下載信用卡撥款對帳資料檔
     */
    function FundingReconDetail($target = "_self"){
        $arParameters = array_merge( array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType) ,$this->Funding);
        ECPay_FundingReconDetail::CheckOut($target,$arParameters,$this->HashKey,$this->HashIV,$this->ServiceURL);
    }

    /**
     * @return array
     * @throws \Exception
     *
     * 產生訂單(站內付) v1.0.11128 wesley
     */
    function CreateTrade() {
        $arParameters = array_merge( array('MerchantID' => $this->MerchantID, 'EncryptType' => $this->EncryptType) ,$this->Send);
        return $arFeedback = ECPay_CreateTrade::CheckOut($arParameters,$this->SendExtend,$this->HashKey,$this->HashIV,$this->ServiceURL);
    }
}