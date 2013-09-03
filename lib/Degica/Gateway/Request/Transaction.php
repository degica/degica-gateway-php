<?php

namespace Degica\Gateway\Request;

class Transaction {
    private $locale;

    public function __construct($locale = 'ja') {
        $this->locale = $locale;
    }

    public function getSignedUrl(Degica\Gateway\Transaction $transaction) {
        $secret_key = $transaction->merchant->api_key;
        $endpoint = "/{$this->locale}/api/{$transaction->merchant->merchant_slug}/transactions/{$transaction->payment_method}/new";

        $params = array(
        "transaction[amount]={$transaction->amount}",
        "transaction[currency]={$transaction->currency}",
        "transaction[external_order_num]={$transaction->external_order_num}",
        "transaction[return_url]={$tranasction->return_url}",
        "transaction[cancel_url]={$transaction->cancel_url}",
        "transaction[tax]={$transaction->tax}",
        "timestamp=" . time(),
        );
        sort($params);

        $query_string = urldecode(implode('&', $params));
        $url = $endpoint . '?' . $query_string;

        $hmac = hash_hmac('sha256', $url, $secret_key);

        return "$url&hmac=$hmac\n";
    }
}
