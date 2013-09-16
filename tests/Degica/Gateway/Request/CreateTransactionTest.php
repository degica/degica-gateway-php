<?php

namespace Degica\Gateway\Request;

use \Degica\Gateway\Customer;

class CreateTransactionTest extends \PHPUnit_Framework_TestCase
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

        $this->create_transaction = new CreateTransaction();
        $this->create_transaction->time = 1234567890;
    }

    public function testSignedUrl()
    {
        $this->transaction->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));
        $expected = 'https://gateway.degica.com/ja/api/foo/transactions/credit_card/new?timestamp=1234567890&transaction[amount]=10.00&transaction[cancel_url]=http://example.com/cancel&transaction[currency]=JPY&transaction[external_order_num]=R12333&transaction[return_url]=http://example.com/success&transaction[tax]=1.20&hmac=620b0aad5f6112a29c196d3177acc6fdac58e0432352611bec668e8c039ecb1c';
        $this->assertEquals($expected, $this->create_transaction->getSignedUrl($this->transaction));
    }

    public function testSandboxMode()
    {
        $this->transaction->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));
        $this->create_transaction->sandboxMode();

        $expected = 'https://gateway-sandbox.degica.com/ja/api/foo/transactions/credit_card/new?timestamp=1234567890&transaction[amount]=10.00&transaction[cancel_url]=http://example.com/cancel&transaction[currency]=JPY&transaction[external_order_num]=R12333&transaction[return_url]=http://example.com/success&transaction[tax]=1.20&hmac=620b0aad5f6112a29c196d3177acc6fdac58e0432352611bec668e8c039ecb1c';
        $this->assertEquals($expected, $this->create_transaction->getSignedUrl($this->transaction));
    }

    public function testCustomerInformation()
    {
        $this->transaction->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->transaction->expects($this->any())
            ->method('getCustomer')
            ->will($this->returnValue($this->customer()));

        $expected = 'https://gateway.degica.com/ja/api/foo/transactions/credit_card/new?timestamp=1234567890&transaction[amount]=10.00&transaction[cancel_url]=http://example.com/cancel&transaction[currency]=JPY&transaction[customer][family_name]=Smith&transaction[customer][given_name]=John&transaction[external_order_num]=R12333&transaction[return_url]=http://example.com/success&transaction[tax]=1.20&hmac=0240721464b33fad061a9d6832b8cac4329e71d6896fe7598a773e544859d6f5';
        $this->assertEquals($expected, $this->create_transaction->getSignedUrl($this->transaction));
    }

    public function testBillingAddress()
    {
        $this->transaction->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));

        $customer = $this->customer();
        $this->transaction->expects($this->any())
            ->method('getCustomer')
            ->will($this->returnValue($customer));

        $customer->expects($this->any())
            ->method('getBillingAddress')
            ->will($this->returnValue($this->address()));

        $expected = 'transaction[customer][billing_address][city]=Kichijoji&transaction[customer][billing_address][country]=jp&transaction[customer][billing_address][extended_address]=Suite ABC&transaction[customer][billing_address][phone]=999-999-9999&transaction[customer][billing_address][state]=Toyko&transaction[customer][billing_address][street_address]=123 Sesame Street&transaction[customer][billing_address][zip_code]=123-4567';
        $this->assertContains($expected, $this->create_transaction->getSignedUrl($this->transaction));
    }

    /**
     * @expectedException \Degica\Gateway\Request\InvalidTransactionException
     */
    public function testInvalidTransaction()
    {
        $this->transaction->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(false));
        $this->create_transaction->getSignedUrl($this->transaction);
    }

    private function customer()
    {
        $customer = $this->getMockBuilder('\Degica\Gateway\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $customer->expects($this->any())
            ->method('getGivenName')
            ->will($this->returnValue('John'));

        $customer->expects($this->any())
            ->method('getFamilyName')
            ->will($this->returnValue('Smith'));

        return $customer;
    }

    private function address()
    {
        $address = $this->getMockBuilder('\Degica\Gateway\Address')
            ->getMock();

        $stubs = array(
            'getCountry' => 'jp',
            'getPostalCode' => '123-4567',
            'getState' => 'Toyko',
            'getCity' => 'Kichijoji',
            'getStreetAddress' => '123 Sesame Street',
            'getExtendedAddress' => 'Suite ABC',
            'getPhone' => '999-999-9999',
        );

        foreach ($stubs as $method => $val) {
            $address->expects($this->any())
                ->method($method)
                ->will($this->returnValue($val));
        }
        return $address;
    }
}
