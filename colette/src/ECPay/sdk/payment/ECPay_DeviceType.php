<?php

namespace App\ECPay\sdk\payment;

abstract class ECPay_DeviceType {

    /**
     * 桌機版付費頁面。
     */
    const PC = 'P';

    /**
     * 行動裝置版付費頁面。
     */
    const Mobile = 'M';

}