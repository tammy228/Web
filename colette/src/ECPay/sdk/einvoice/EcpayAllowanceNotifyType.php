<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:45 PM
 */

namespace App\ECPay\sdk\einvoice;

abstract class EcpayAllowanceNotifyType
{
    // 簡訊通知
    const Sms = 'S';

    // 電子郵件通知
    const Email = 'E';

    // 皆通知
    const All = 'A';

    // 皆不通知
    const None = 'N';
}