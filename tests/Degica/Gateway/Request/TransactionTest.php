<?php

class TransactionTest extends PHPUnit_Framework_TestCase
{
    public function testSignedUrl()
    {
        $merchant = new Degica\Gateway\Merchant();
        $merchant->setMerchantSlug('foo');
        $merchant->setApiKey('ABCD1234');

        $transaction = new Degica\Gateway\Transaction($merchant);
        $transaction->setAmount('10.00');
        $transaction->setCurrency('JPY');
        $transaction->setExternalOrderNum('O12333');
        $transaction->setReturnUrl('http://example.com/success');
        $transaction->setCancelUrl('http://example.com/cancel');
        $transaction->setTax('1.20');

        $transaction_request = new Degica\Gateway\Request\Transaction();
        $transaction_request->time = 1234567890;
        $this->assertEquals('/ja/api/foo/transactions/credit_card/new?timestamp=1234567890&transaction[amount]=10.00&transaction[cancel_url]=http://example.com/cancel&transaction[currency]=JPY&transaction[external_order_num]=O12333&transaction[return_url]=http://example.com/success&transaction[tax]=1.20&hmac=4898430789dab005a80f4aafb00172949b171588459c61eddab3821215515f97', $transaction_request->getSignedUrl($transaction));
    }
}
