<?php

namespace Degica\Gateway\Callback;

use Degica\Gateway\Merchant;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function setUp() {
        $this->merchant = new Merchant();
        $this->merchant->setMerchantSlug('slug');
        $this->merchant->setApiKey('api');

        $this->params = array(
            'hmac' => 'd12a0ac92f3e0b02bba21b94fdbaea2d09369e623da72ed7c4f13dda13e8163b',
            'timestamp' => '1234567890',
            'transaction' => array(
                'amount' => '100',
                'currency' => 'JPY',
                'external_order_num' => 'R123',
                'payment_method' => 'konbini',
                'status' => 'captured',
                'tax' => '5',
                'uuid' => 'UUID-1123b',
            ),
        );
    }

    public function testGetTransaction() {
        $request = new Request($this->merchant, '/callback.php', $this->params);
        $request->time = '1234567890';
        $expected = new Transaction();
        $expected->setAmount('100');
        $expected->setCurrency('JPY');
        $expected->setExternalOrderNum('R123');
        $expected->setPaymentMethod('konbini');
        $expected->setStatus('captured');
        $expected->setTax('5');
        $expected->setUuid('UUID-1123b');

        $this->assertEquals($expected, $request->getTransaction());
    }

    /**
     * @expectedException Degica\Gateway\Callback\InvalidHmacException
     */
    public function testInvalidHmac() {
        $this->params['hmac'] = 'WRONG';
        $request = new Request($this->merchant, '/callback.php', $this->params);
        $request->getTransaction();
    }

    /**
     * @expectedException Degica\Gateway\Callback\ExpiredRequestException
     */
    public function testExpiredRequest() {
        $this->params['timestamp'] = '1';
        $this->params['hmac'] = 'f1572d46a4296b088c0a3ceb7e162bdc16b91b8f3fda7f035a83e690d246bc4a';
        $request = new Request($this->merchant, '/callback.php', $this->params);
        $request->getTransaction();
    }
}

