<?php

namespace App\ECPay\sdk\payment;

abstract class ECPay_ExtraPaymentInfo {

    /**
     * 需要額外付款資訊。
     */
    const Yes = 'Y';

    /**
     * 不需要額外付款資訊。
     */
    const No = 'N';

}