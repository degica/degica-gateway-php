<?php

namespace Degica\Gateway;

class PaymentMethod {
    const BANK_TRANSFER = 'bank_transfer';
    const CREDIT_CARD = 'credit_card';
    const KONBINI = 'konbini';
    const MOBILE_PAYMENT = 'mobile_payment';
    const PAY_EASY = 'pay_easy';
    const PAYPAL = 'paypal';

    public static function all()
    {
        return array(
            self::BANK_TRANSFER,
            self::CREDIT_CARD,
            self::KONBINI,
            self::MOBILE_PAYMENT,
            self::PAY_EASY,
            self::PAYPAL,
        );
    }
}
