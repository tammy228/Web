<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:46 PM
 */

namespace App\ECPay\sdk\einvoice;

abstract class EcpayNotifiedType
{
    // 通知客戶
    const Customer = 'C';

    // 通知廠商
    const vendor = 'M';

    // 皆發送
    const All = 'A';
}