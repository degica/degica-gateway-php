<?php

namespace Degica\Gateway;

class Transaction {
    private $merchant;
    public $payment_method = PaymentMethod::CREDIT_CARD;
    public $amount;
    public $cancel_url;
    public $currency = 'JPY';
    public $external_order_num;
    public $return_url;
    public $tax;

    function __construct(Merchant $merchant) {
        $this->merchant = $merchant;
    }
}
