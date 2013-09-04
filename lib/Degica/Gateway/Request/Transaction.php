<?php

namespace Degica\Gateway\Request;

class Transaction {
    private $locale;
    public $time;

    public function __construct($locale = 'ja') {
        $this->locale = $locale;
        $this->time = time();
    }

    public function getSignedUrl(\Degica\Gateway\Transaction $transaction) {
        $secret_key = $transaction->getMerchant()->getApiKey();
        $endpoint = "/{$this->locale}/api/{$transaction->getMerchant()->getMerchantSlug()}/transactions/{$transaction->payment_method}/new";

        $params = array(
        "transaction[amount]={$transaction->amount}",
        "transaction[currency]={$transaction->currency}",
        "transaction[external_order_num]={$transaction->external_order_num}",
        "transaction[return_url]={$transaction->return_url}",
        "transaction[cancel_url]={$transaction->cancel_url}",
        "transaction[tax]={$transaction->tax}",
        "timestamp=" . $this->time,
        );
        sort($params);

        $query_string = urldecode(implode('&', $params));
        $url = $endpoint . '?' . $query_string;

        $hmac = hash_hmac('sha256', $url, $secret_key);

        return "$url&hmac=$hmac";
    }
}
