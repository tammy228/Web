<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:43 PM
 */

namespace App\ECPay\sdk\einvoice;

abstract class EcpayClearanceMark
{
    // 經海關出口
    const Yes = '1';

    // 非經海關出口
    const No = '2';
}