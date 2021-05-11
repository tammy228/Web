<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:50 PM
 */

namespace App\ECPay\sdk\einvoice;

class ECPay_INVOICE
{
    // 所需參數
    public $parameters = array(
        'TimeStamp'		=>'',
        'MerchantID'		=>'',
        'RelateNumber'		=>'',
        'CustomerID'		=>'',
        'CustomerIdentifier'	=>'',
        'CustomerName'		=>'',
        'CustomerAddr'		=>'',
        'CustomerPhone'		=>'',
        'CustomerEmail'		=>'',
        'ClearanceMark'		=>'',
        'Print'			=>'',
        'Donation'		=>'',
        'LoveCode'		=>'',
        'CarruerType'		=>'',
        'CarruerNum'		=>'',
        'TaxType'		=>'',
        'SalesAmount'		=>'',
        'InvoiceRemark'		=>'',
        'Items'			=>array(),
        'ItemName'		=>'',
        'ItemCount'		=>'',
        'ItemWord'		=>'',
        'ItemPrice'		=>'',
        'ItemTaxType'		=>'',
        'ItemAmount'		=>'',
        'ItemRemark'		=>'',
        'CheckMacValue'		=>'',
        'InvType'		=>'',
        'vat' 			=>'',
        'OnLine' 		=> true
    );

    // 需要做urlencode的參數
    public $urlencode_field = array(
        'CustomerName' 		=>'',
        'CustomerAddr'		=>'',
        'CustomerEmail'		=>'',
        'InvoiceRemark'		=>'',
        'ItemName' 		=>'',
        'ItemWord'		=>'',
        'ItemRemark' 		=>''
    );

    // 不需要送壓碼的欄位
    public $none_verification = array(
        'InvoiceRemark' 	=>'',
        'ItemName' 		=>'',
        'ItemWord'		=>'',
        'ItemRemark' 		=>'',
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

        $nItems_Count_Total = 0 ;
        $nItems_Foreach_Count = 1 ;
        $sItemName = '' ;
        $sItemCount = '' ;
        $sItemWord = '' ;
        $sItemPrice = '' ;
        $sItemTaxType = '' ;
        $sItemAmount = '' ;
        $sItemRemark = '' ;

        foreach ($this->parameters as $key => $value) {
            if(isset($arParameters[$key])) {
                $this->parameters[$key] = $arParameters[$key];
            }
        }

        // 商品資訊組合
        $nItems_Count_Total = count($arParameters['Items']) ;	// 商品總筆數

        if($nItems_Count_Total != 0) {

            foreach($arParameters['Items'] as $key2 => $value2) {
                $sItemName 	.= (isset($value2['ItemName']))		? $value2['ItemName'] 		: '' ;
                $sItemCount 	.= (int) $value2['ItemCount'] ;
                $sItemWord 	.= (isset($value2['ItemWord'])) 	? $value2['ItemWord'] 		: '' ;
                $sItemPrice 	.= $value2['ItemPrice'] ;
                $sItemTaxType 	.= (isset($value2['ItemTaxType'])) 	? $value2['ItemTaxType'] 	: '' ;
                $sItemAmount	.= $value2['ItemAmount'] ;
                $sItemRemark 	.= (isset($value2['ItemRemark'])) 	? $value2['ItemRemark'] 	: '' ;

                if( $nItems_Foreach_Count < $nItems_Count_Total ) {
                    $sItemName .= '|' ;
                    $sItemCount .= '|' ;
                    $sItemWord .= '|' ;
                    $sItemPrice .= '|' ;
                    $sItemTaxType .= '|' ;
                    $sItemAmount .= '|' ;
                    $sItemRemark .= '|' ;
                }

                $nItems_Foreach_Count++ ;
            }
        }

        $this->parameters['ItemName'] 		= $sItemName;		// 商品名稱
        $this->parameters['ItemCount'] 		= $sItemCount ;
        $this->parameters['ItemWord'] 		= $sItemWord;		// 商品單位
        $this->parameters['ItemPrice'] 		= $sItemPrice ;
        $this->parameters['ItemTaxType'] 	= $sItemTaxType ;
        $this->parameters['ItemAmount'] 	= $sItemAmount ;
        $this->parameters['ItemRemark'] 	= $sItemRemark ;	// 商品備註

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
        $nCheck_Amount = 0 ; 	// 驗證總金額

        // 4.廠商自訂編號

        // *預設不可為空值
        if(strlen($arParameters['RelateNumber']) == 0) {
            array_push($arErrors, '4:RelateNumber is required.');
        }

        // *預設最大長度為30碼
        if(strlen($arParameters['RelateNumber']) > 30) {
            array_push($arErrors, '4:RelateNumber max langth as 30.');
        }

        // 5.客戶編號 CustomerID

        // *預設最大長度為20碼
        if(strlen($arParameters['CustomerID']) > 20 ) {
            array_push($arErrors, '5:CustomerID max langth as 20.');
        }

        // *比對客戶編號 只接受英、數字與下底線格式
        if(strlen($arParameters['CustomerID']) > 0) {
            if( !preg_match('/^[a-zA-Z0-9_]+$/', $arParameters['CustomerID']) ) {
                arRay_push($arErrors, '5:Invalid CustomerID.');
            }
        }

        // 6.統一編號判斷 CustomerIdentifier

        // *若統一編號有值時，則固定長度為數字8碼
        if( strlen( $arParameters['CustomerIdentifier'] ) > 0  ) {
            if( !preg_match('/^[0-9]{8}$/', $arParameters['CustomerIdentifier']) ) {
                array_push($arErrors, '6:CustomerIdentifier length should be 8.');
            }
        }

        // 7.客戶名稱 CustomerName
        // x僅能為中英數字格式
        // *若列印註記 = '1' (列印)時，則客戶名稱須有值
        if ($arParameters['Print'] == EcpayPrintMark::Yes) {
            if (mb_strlen($arParameters['CustomerName'], 'UTF-8') == 0 && $arParameters['OnLine']) {
                array_push($arErrors, "7:CustomerName is required.");
            }
        }

        // *預設最大長度為30碼
        if( mb_strlen($arParameters['CustomerName'], 'UTF-8') > 60) {
            array_push($arErrors, '7:CustomerName max length as 60.');
        }

        // 8.客戶地址 CustomerAddr(UrlEncode, 預設為空字串)

        // *若列印註記 = '1' (列印)時，則客戶地址須有值
        if ($arParameters['Print'] == EcpayPrintMark::Yes) {
            if (mb_strlen($arParameters['CustomerAddr'], 'UTF-8') == 0 && $arParameters['OnLine']) {
                array_push($arErrors, "8:CustomerAddr is required.");
            }
        }

        // *預設最大長度為100碼
        if (mb_strlen($arParameters['CustomerAddr'], 'UTF-8') > 100) {
            array_push($arErrors, "8:CustomerAddr max length as 100.");
        }

        // 9.客戶手機號碼 CustomerPhone
        // *預設最小長度為1碼，最大長度為20碼
        if (strlen($arParameters['CustomerPhone']) > 20) {
            array_push($arErrors, "9:CustomerPhone max length as 20.");
        }

        // *預設格式為數字組成
        if (strlen($arParameters['CustomerPhone']) > 0) {
            if( !preg_match('/^[0-9]*$/', $arParameters['CustomerPhone']) ) {
                array_push($arErrors, '9:Invalid CustomerPhone.');
            }
        }

        // 10.客戶電子信箱 CustomerEmail(UrlEncode, 預設為空字串, 與CustomerPhone擇一不可為空)

        // *預設最大長度為80碼
        if (strlen($arParameters['CustomerEmail']) > 80) {
            array_push($arErrors, "10:CustomerEmail max length as 80.");
        }

        // *若客戶電子信箱有值時，則格式僅能為Email的標準格式
        if(strlen($arParameters['CustomerEmail']) > 0 ) {
            if ( !preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-_]+\.([a-z0-9\-_]+\.)*?[a-z]+$/is', $arParameters['CustomerEmail']) ) {
                array_push($arErrors, '10:Invalid CustomerEmail Format.');
            }
        }

        // 9. 10.
        // *若客戶手機號碼為空值時，則客戶電子信箱不可為空值
        if (strlen($arParameters['CustomerPhone']) == 0 && strlen($arParameters['CustomerEmail']) == 0 && $arParameters['OnLine']) {
            array_push($arErrors, "9-10:CustomerPhone or CustomerEmail is required.");
        }

        // 11.通關方式 ClearanceMark(預設為空字串)
        // *最多1字元
        if (strlen($arParameters['ClearanceMark']) > 1) {
            array_push($arErrors, "11:ClearanceMark max length as 1.");
        }

        // *課稅類別為零稅率(Zero)或課稅類別為混合稅率(Mix)且商品課稅別存在零稅率時，此參數不可為空字串
        if ($arParameters['TaxType'] == EcpayTaxType::Zero || ($arParameters['TaxType'] == EcpayTaxType::Mix && strpos($arParameters['ItemTaxType'], EcpayTaxType::Zero) !== false)) {
            if ( ( $arParameters['ClearanceMark'] != EcpayClearanceMark::Yes ) && ( $arParameters['ClearanceMark'] != EcpayClearanceMark::No ) ) {
                array_push($arErrors, "11:ClearanceMark is required.");
            }
        }

        // 12.列印註記 Print(預設為No)
        // *列印註記僅能為 0 或 1
        if ( ( $arParameters['Print'] != EcpayPrintMark::Yes ) && ( $arParameters['Print'] != EcpayPrintMark::No ) ) {
            array_push($arErrors, "12:Invalid Print.");
        }

        // *若捐贈註記 = '1' (捐贈)時，則VAL = '0' (不列印)
        if ($arParameters['Donation'] == EcpayDonation::Yes) {
            if ($arParameters['Print'] != EcpayPrintMark::No) {
                array_push($arErrors, "12:Donation Print should be No.");
            }
        }

        // *若統一編號有值時，則VAL = '1' (列印)
        if (strlen($arParameters['CustomerIdentifier']) > 0) {
            if ($arParameters['Print'] != EcpayPrintMark::Yes) {
                array_push($arErrors, "12:CustomerIdentifier Print should be Yes.");
            }
        }

        // 線下列印判斷
        // 1200079當線下廠商開立發票無載具且無統一編號時，必須列印。
        if($arParameters['OnLine'] === false) {
            if( ($arParameters['CarruerType'] == EcpayCarruerType::None ) && strlen($arParameters['CustomerIdentifier']) == 0 ) {
                if ($arParameters['Print'] != EcpayPrintMark::Yes) {
                    array_push($arErrors, "12:Offline Print should be Yes.");
                }
            }
        }

        // 13.捐贈註記 Donation

        // *固定給定下述預設值若為捐贈時，則VAL = '1'，若為不捐贈時，則VAL = '0'
        if ( ($arParameters['Donation'] != EcpayDonation::Yes ) && ( $arParameters['Donation'] != EcpayDonation::No ) ) {
            array_push($arErrors, "13:Invalid Donation.");
        }

        // *若統一編號有值時，則VAL = '2' (不捐贈)
        if (strlen($arParameters['CustomerIdentifier']) > 0 && $arParameters['Donation'] == EcpayDonation::Yes ) {
            array_push($arErrors, "13:CustomerIdentifier Donation should be No.");
        }


        // 14.愛心碼 LoveCode(預設為空字串)

        // *若捐贈註記 = '1' (捐贈)時，則須有值
        if ($arParameters['Donation'] == EcpayDonation::Yes) {
            if ( !preg_match('/^([xX]{1}[0-9]{2,6}|[0-9]{3,7})$/', $arParameters['LoveCode']) ) {
                array_push($arErrors, "14:Invalid LoveCode.");
            }
        } else {
            if (strlen($arParameters['LoveCode']) > 0) {
                array_push($arErrors, "14:Please remove LoveCode.");
            }
        }

        // 15.載具類別 CarruerType(預設為None)

        // *固定給定下述預設值None、Member、Cellphone
        if ( ( $arParameters['CarruerType'] != EcpayCarruerType::None ) && ( $arParameters['CarruerType'] != EcpayCarruerType::Member ) && ( $arParameters['CarruerType'] != EcpayCarruerType::Citizen ) && ( $arParameters['CarruerType'] != EcpayCarruerType::Cellphone ) ) {
            array_push($arErrors, "15:Invalid CarruerType.");
        } else {
            // *統一編號不為空字串時，則載具類別不可為會載具或自然人憑證載具
            if (strlen($arParameters['CustomerIdentifier']) > 0) {
                if ($arParameters['CarruerType'] == EcpayCarruerType::Member || $arParameters['CarruerType'] == EcpayCarruerType::Citizen ) {
                    array_push($arErrors, "15:Invalid CarruerType.");
                }
            }
        }

        // 16.載具編號 CarruerNum(預設為空字串)
        switch ($arParameters['CarruerType']) {

            // *載具類別為無載具(None)或會員載具(Member)時，請設定空字串
            case EcpayCarruerType::None:
            case EcpayCarruerType::Member:
                if (strlen($arParameters['CarruerNum']) > 0) {
                    array_push($arErrors, "16:Please remove CarruerNum.");
                }
                break;

            // *載具類別為買受人自然人憑證(Citizen)時，請設定自然人憑證號碼，前2碼為大小寫英文，後14碼為數字
            // NOTE:API程式會自動將小寫轉成大寫
            case EcpayCarruerType::Citizen:
                if ( !preg_match('/^[a-zA-Z]{2}\d{14}$/', $arParameters['CarruerNum']) ) {
                    array_push($arErrors, "16:Invalid CarruerNum.");
                }
                break;

            // *載具類別為買受人手機條碼(Cellphone)時，請設定手機條碼，第1碼為「/」，後7碼為大寫英文、數字、「+」、「-」或「.」
            case EcpayCarruerType::Cellphone:
                if ( !preg_match('/^\/{1}[0-9A-Z+-.]{7}$/', $arParameters['CarruerNum']) ) {
                    array_push($arErrors, "16:Invalid CarruerNum.");
                }
                break;

            default:
                array_push($arErrors, "16:Please remove CarruerNum.");
        }

        // 17.課稅類別 TaxType(不可為空)

        // *不可為空
        if (strlen($arParameters['TaxType']) == 0) {
            array_push($arErrors, "17:TaxType is required.");
        }

        // *僅能為 1應稅 2零稅率 3免稅 9.應稅與免稅混合
        if ( ( $arParameters['TaxType'] != EcpayTaxType::Dutiable ) && ( $arParameters['TaxType'] != EcpayTaxType::Zero ) && ( $arParameters['TaxType'] != EcpayTaxType::Free ) && ( $arParameters['TaxType'] != EcpayTaxType::Mix ) ) {
            array_push($arErrors, "17:Invalid TaxType.");
        }

        // 18.發票金額 SalesAmount

        // *不可為空
        if (strlen($arParameters['SalesAmount']) == 0) {
            array_push($arErrors, "18:SalesAmount is required.");
        }

        // 20.21.22.23.24.25. 商品資訊

        // *不可為空
        if (sizeof($arParameters['Items']) == 0) {
            array_push($arErrors, '20-25:Items is required.');
        } else {

            // 檢查是否存在保留字元 '|'
            $bFind_Tag = true;
            $bError_Tag = false;

            foreach($arParameters['Items'] as $key => $value) {

                $bFind_Tag = strpos($value['ItemName'], '|') ;
                if($bFind_Tag != false || empty($value['ItemName'])) {
                    $bError_Tag = true ;
                    array_push($arErrors, '20:Invalid ItemName.');
                    break;
                }

                $bFind_Tag = strpos($value['ItemCount'], '|') ;
                if($bFind_Tag != false || empty($value['ItemCount'])) {
                    $bError_Tag = true ;
                    array_push($arErrors, '21:Invalid ItemCount.');
                    break;
                }

                $bFind_Tag = strpos($value['ItemWord'], '|') ;
                if($bFind_Tag != false || empty($value['ItemWord'])) {
                    $bError_Tag = true ;
                    array_push($arErrors, '22:Invalid ItemWord.');
                    break;
                }

                $bFind_Tag = strpos($value['ItemPrice'], '|') ;
                if($bFind_Tag != false || $value['ItemPrice'] === '') {
                    $bError_Tag = true ;
                    array_push($arErrors, '23:Invalid ItemPrice.');
                    break;
                }

                $bFind_Tag = strpos($value['ItemTaxType'], '|') ;
                if($bFind_Tag != false || empty($value['ItemTaxType'])) {
                    $bError_Tag = true ;
                    array_push($arErrors, '24:Invalid ItemTaxType.');
                    break;
                }

                $bFind_Tag = strpos($value['ItemAmount'], '|') ;
                if($bFind_Tag != false || $value['ItemAmount'] === '' ) {
                    $bError_Tag = true ;
                    array_push($arErrors, '25:Invalid ItemAmount.');
                    break;
                }

                // V1.0.3
                if(isset($value['ItemRemark'])) {
                    $bFind_Tag = strpos($value['ItemRemark'], '|') ;
                    if($bFind_Tag != false || empty($value['ItemRemark'])) {
                        $bError_Tag = true ;
                        array_push($arErrors, '143:Invalid ItemRemark.');
                        break;
                    }
                }
            }

            // 檢查商品格式
            if(!$bError_Tag) {

                foreach($arParameters['Items'] as $key => $value) {

                    // *ItemCount數字判斷
                    if ( !preg_match('/^[0-9]*$/', $value['ItemCount']) ) {
                        array_push($arErrors, '21:Invalid ItemCount.');
                    }

                    // *ItemWord 預設最大長度為6碼
                    if (strlen($value['ItemWord']) > 6 ) {
                        array_push($arErrors, '22:ItemWord max length as 6.');
                    }

                    // *ItemPrice數字判斷
                    if ( !preg_match('/(^[-0-9]*$)|([0-9]+\.[0-9]+)/', $value['ItemPrice']) ) {
                        array_push($arErrors, '23:Invalid ItemPrice.');
                    }

                    // *ItemAmount數字判斷
                    if ( !preg_match('/(^[-0-9]*$)|([0-9]+\.[0-9]+)/', $value['ItemAmount']) ) {
                        array_push($arErrors, '25:Invalid ItemAmount.');
                    } else {
                        $nCheck_Amount = $nCheck_Amount + $value['ItemAmount'] ;
                    }

                    // V1.0.3
                    // *ItemRemark 預設最大長度為40碼 如果有此欄位才判斷
                    if(isset($value['ItemRemark'])) {
                        if (strlen($value['ItemRemark']) > 40 ) {
                            array_push($arErrors, '143:ItemRemark max length as 40.');
                        }
                    }
                }


                // *檢查商品總金額
                if ( $arParameters['SalesAmount'] != round($nCheck_Amount)) {
                    array_push($arErrors, "18.2:Invalid SalesAmount.");
                }

                // *檢查商品課稅別
                // 課稅類別為混合稅率時
                if ($arParameters['TaxType'] == EcpayTaxType::Mix) {

                    $ItemTaxType = explode("|", $arParameters['ItemTaxType']);
                    // 商品課稅別不可為空
                    if(empty($arParameters['ItemTaxType'])) {
                        array_push($arErrors, "24:ItemTaxType is required.");
                    }
                    // 需含二筆或以上的商品課稅別
                    if ( count($ItemTaxType) < 2) {
                        array_push($arErrors, "24:ItemTaxType should be more than 2.");
                    }

                    // 免稅和零稅率發票不能同時開立
                    // 只能有兩種情形 :
                    // 1.應稅+免稅
                    // 2.應稅+零稅率
                    $items = array_unique($ItemTaxType);
                    if ( count($items) != 2 || !in_array(1, $items)) {
                        array_push($arErrors, "24:ItemTaxType error.");
                    }
                }
            }
        }

        // 27.字軌類別

        // *InvType(不可為空) 僅能為 07 狀態
        if( ( $arParameters['InvType'] != EcpayInvType::General ) ) {
            array_push($arErrors, "27:Invalid InvType.");
        }

        // 29.商品單價是否含稅(預設為含稅價)

        // *固定給定下述預設值 若為含稅價，則VAL = '1'
        if(!empty($arParameters['vat'])) {
            if( ( $arParameters['vat'] != EcpayVatType::Yes ) && ( $arParameters['vat'] != EcpayVatType::No ) ) {
                array_push($arErrors, "29:Invalid VatType.");
            }
        }

        if(sizeof($arErrors)>0){
            throw new \Exception(join('<br>', $arErrors));
        }

        // 刪除items
        unset($arParameters['Items']);

        // 刪除SDK自訂義參數
        unset($arParameters['OnLine']);

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
        if(isset($arParameters['CarruerNum'])) {
            // 載具編號內包含+號則改為空白
            $arParameters['CarruerNum'] = str_replace('+',' ',$arParameters['CarruerNum']);
        }

        return $arParameters ;
    }
}
