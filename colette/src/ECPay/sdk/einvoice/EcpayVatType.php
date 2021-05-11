<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:44 PM
 */

namespace App\ECPay\sdk\einvoice;

abstract class EcpayVatType
{
    // 商品單價含稅價
    const Yes = '1';

    // 商品單價未稅價
    const No = '0';
}