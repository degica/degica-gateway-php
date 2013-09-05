<?php

namespace Degica\Gateway\Request;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $merchant = new \Degica\Gateway\Merchant();
        $merchant->setMerchantSlug('foo');
        $merchant->setApiKey('ABCD1234');

        $transaction = $this->getMockBuilder('\Degica\Gateway\Transaction')
            ->disableOriginalConstructor()
            ->getMock();

        $stubs = array(
            'getMerchant' => $merchant,
            'getAmount' => '10.00',
            'getCurrency' => 'JPY',
            'getExternalOrderNum' => 'R12333',
            'getReturnUrl' => 'http://example.com/success',
            'getCancelUrl' => 'http://example.com/cancel',
            'getTax' => '1.20',
            'getPaymentMethod' => 'credit_card',
        );

        foreach ($stubs as $key => $val) {
            $transaction->expects($this->any())
                ->method($key)
                ->will($this->returnValue($val));
        }

        $this->transaction = $transaction;

        $this->transaction_request = new Transaction();
        $this->transaction_request->time = 1234567890;
    }

    public function testSignedUrl()
    {
        $this->transaction->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));
        $expected = '/ja/api/foo/transactions/credit_card/new?timestamp=1234567890&transaction[amount]=10.00&transaction[cancel_url]=http://example.com/cancel&transaction[currency]=JPY&transaction[external_order_num]=R12333&transaction[return_url]=http://example.com/success&transaction[tax]=1.20&hmac=620b0aad5f6112a29c196d3177acc6fdac58e0432352611bec668e8c039ecb1c';
        $this->assertEquals($expected, $this->transaction_request->getSignedUrl($this->transaction));
    }

    /**
     * @expectedException \Degica\Gateway\Request\InvalidTransactionException
     */
    public function testInvalidTransaction()
    {
        $this->transaction->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));
        $this->transaction_request->getSignedUrl($this->transaction);
    }
}
