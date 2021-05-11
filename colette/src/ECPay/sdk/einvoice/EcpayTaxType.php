<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:43 PM
 */

namespace App\ECPay\sdk\einvoice;

abstract class EcpayTaxType
{
    // 應稅
    const Dutiable = '1';

    // 零稅率
    const Zero = '2';

    // 免稅
    const Free = '3';

    // 應稅與免稅混合(限收銀機發票無法分辦時使用，且需通過申請核可)
    const Mix = '9';
}