<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:44 PM
 */

namespace App\ECPay\sdk\einvoice;

abstract class EcpayDelayFlagType
{
    // 延遲註記
    const Delay = '1';

    // 觸發註記
    const Trigger = '2';
}