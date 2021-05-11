<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/19
 * Time: 4:33 PM
 */

namespace App\ECPay\sdk\einvoice;

abstract class EcpayCarruerType
{
    // 無載具
    const None = '';

    // 會員載具
    const Member = '1';

    // 買受人自然人憑證
    const Citizen = '2';

    // 買受人手機條碼
    const Cellphone = '3';
}