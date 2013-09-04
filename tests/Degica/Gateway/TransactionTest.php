<?php

namespace Degica\Gateway;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $merchant = new Merchant();
        $this->transaction = new Transaction($merchant);
    }

    public function testNumericTax()
    {
        $this->transaction->setTax(0);
        $this->assertEquals($this->transaction->getTax(), 0);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNonNumericTax()
    {
        $this->transaction->setTax('foo');
    }

    public function testValidReturnUrl()
    {
        $this->transaction->setReturnUrl('http://foo.bar/?test=what');
        $this->assertEquals($this->transaction->getReturnUrl(), 'http://foo.bar/?test=what');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidReturnUrl()
    {
        $this->transaction->setReturnUrl('foo');
    }

    public function testValidCurrency()
    {
        $this->transaction->setCurrency('JPY');
        $this->assertEquals($this->transaction->getCurrency(), 'JPY');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCurrency()
    {
        $this->transaction->setCurrency('USD');
    }

    public function testValidExternalOrderNumber()
    {
        $this->transaction->setExternalOrderNum('ABC1234');
        $this->assertEquals($this->transaction->getExternalOrderNum(), 'ABC1234');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage External Order Number is greater than 255 characters
     */
    public function testInvalidExternalOrderNumber()
    {
        $long_string = str_pad('', 256, '-');
        $this->transaction->setExternalOrderNum($long_string);
    }

    public function testValidCancelUrl()
    {
        $this->transaction->setCancelUrl('http://cancel.me/');
        $this->assertEquals($this->transaction->getCancelUrl(), 'http://cancel.me/');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cancel URL was not a valid URL, got `nope`
     */
    public function testInvalidCancelUrl()
    {
        $this->transaction->setCancelUrl('nope');
    }

    public function testValidAmount()
    {
        $this->transaction->setAmount('1.23');
        $this->assertEquals($this->transaction->getAmount(), '1.23');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Amount must be numeric, got `NaN`
     */
    public function testInvalidAmount()
    {
        $this->transaction->setAmount('NaN');
    }

    public function testPaymentMethod()
    {
        $this->transaction->setPaymentMethod(PaymentMethod::KONBINI);
        $this->assertEquals($this->transaction->getPaymentMethod(), PaymentMethod::KONBINI);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid Payment Method
     */
    public function testInvalidPaymentMethod()
    {
        $this->transaction->setPaymentMethod('something_else');
    }

    public function testInvalidTransaction()
    {
        $this->assertFalse($this->transaction->isValid());
    }

    public function testValidTransaction()
    {
        $this->transaction->setAmount('1.23');
        $this->transaction->setExternalOrderNum('abcd123');
        $this->transaction->setReturnUrl('http://return.to.me/');
        $this->transaction->setCancelUrl('http://cancel.me/');
        $this->transaction->setTax('0.12');
        $this->assertTrue($this->transaction->isValid());
    }
}
