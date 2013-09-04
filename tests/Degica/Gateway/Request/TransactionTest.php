<?php

class TransactionTest extends PHPUnit_Framework_TestCase
{
    public function testSignedUrl()
    {
        $merchant = new Degica\Gateway\Merchant();
        $merchant->merchant_slug = 'foo';
        $merchant->api_key = 'ABCD1234';

        $transaction = new Degica\Gateway\Transaction($merchant);
        $transaction->amount = '10.00';
        $transaction->currency = 'JPY';
        $transaction->external_order_num = 'O12333';
        $transaction->return_url = 'http//example.com/success';
        $transaction->cancel_url = 'http//example.com/cancel';
        $transaction->tax = '1.20';

        $transaction_request = new Degica\Gateway\Request\Transaction();
        $transaction_request->time = 1234567890;
        $this->assertEquals('/ja/api/foo/transactions/credit_card/new?timestamp=1234567890&transaction[amount]=10.00&transaction[cancel_url]=http//example.com/cancel&transaction[currency]=JPY&transaction[external_order_num]=O12333&transaction[return_url]=http//example.com/success&transaction[tax]=1.20&hmac=18653f94c2fb533ea1d2cc7fc27287d8cb6a0e2fea6520c3f0fea46fb9934263', $transaction_request->getSignedUrl($transaction));
    }
}
