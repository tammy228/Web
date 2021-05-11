<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:46 PM
 */

namespace App\ECPay\sdk\einvoice;

abstract class ECPay_EncryptType
{
    // MD5(預設)
    const ENC_MD5 = 0;

    // SHA256
    const ENC_SHA256 = 1;
}