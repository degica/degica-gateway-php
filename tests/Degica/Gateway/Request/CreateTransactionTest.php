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
        $expected = 'https://gateway.degica.com/ja/api/foo/transactions/credit_card/new?timestamp=1234567890&transaction%5Bamount%5D=10.00&transaction%5Bcancel_url%5D=http%3A%2F%2Fexample.com%2Fcancel&transaction%5Bcurrency%5D=JPY&transaction%5Bexternal_order_num%5D=R12333&transaction%5Breturn_url%5D=http%3A%2F%2Fexample.com%2Fsuccess&transaction%5Btax%5D=1.20&hmac=0bf63581771a068c2cbfab9f79b622f3592a85bf842420d373c3ec94e4cd345c';
        $this->assertEquals($expected, $this->create_transaction->getSignedUrl($this->transaction));
    }

    public function testSandboxMode()
    {
        $this->transaction->expects($this->any())
            ->method('isValid')
            ->will($this->returnValue(true));
        $this->create_transaction->sandboxMode();

        $expected = 'https://gateway-sandbox.degica.com/ja/api/foo/transactions/credit_card/new?timestamp=1234567890&transaction%5Bamount%5D=10.00&transaction%5Bcancel_url%5D=http%3A%2F%2Fexample.com%2Fcancel&transaction%5Bcurrency%5D=JPY&transaction%5Bexternal_order_num%5D=R12333&transaction%5Breturn_url%5D=http%3A%2F%2Fexample.com%2Fsuccess&transaction%5Btax%5D=1.20&hmac=0bf63581771a068c2cbfab9f79b622f3592a85bf842420d373c3ec94e4cd345c';
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

        $expected = 'https://gateway.degica.com/ja/api/foo/transactions/credit_card/new?timestamp=1234567890&transaction%5Bamount%5D=10.00&transaction%5Bcancel_url%5D=http%3A%2F%2Fexample.com%2Fcancel&transaction%5Bcurrency%5D=JPY&transaction%5Bcustomer%5D%5Bfamily_name%5D=Smith&transaction%5Bcustomer%5D%5Bgiven_name%5D=John&transaction%5Bexternal_order_num%5D=R12333&transaction%5Breturn_url%5D=http%3A%2F%2Fexample.com%2Fsuccess&transaction%5Btax%5D=1.20&hmac=ee7d1e385b0efddf96ecc8f10dff4a18ecabea4f3a3dec17c2c2e427ab6fc4b0';
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

        $expected = 'transaction%5Bcustomer%5D%5Bbilling_address%5D%5Bcity%5D=Kichijoji&transaction%5Bcustomer%5D%5Bbilling_address%5D%5Bcountry%5D=JP&transaction%5Bcustomer%5D%5Bbilling_address%5D%5Bextended_address%5D=Suite+ABC&transaction%5Bcustomer%5D%5Bbilling_address%5D%5Bphone%5D=999-999-9999&transaction%5Bcustomer%5D%5Bbilling_address%5D%5Bstate%5D=Toyko&transaction%5Bcustomer%5D%5Bbilling_address%5D%5Bstreet_address%5D=123+Sesame+Street&transaction%5Bcustomer%5D%5Bbilling_address%5D%5Bzipcode%5D=123-4567';
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
