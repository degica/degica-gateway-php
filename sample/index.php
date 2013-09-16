<?php

require __DIR__ . '/../vendor/autoload.php';

use Degica\Gateway\Address;
use Degica\Gateway\Customer;
use Degica\Gateway\Merchant;
use Degica\Gateway\PaymentMethod;
use Degica\Gateway\Transaction;

use Degica\Gateway\Request\CreateTransaction;

$merchant = new Merchant();
$merchant->setMerchantSlug('degica-mart');
$merchant->setApiKey('42f89dc68c192645c2a752e907fcf648c2ff6782f7c18dffa0b6decd3ec7b030');

// Basic Transaction Information
$transaction = new Transaction($merchant);
$transaction->setPaymentMethod(PaymentMethod::CREDIT_CARD);
$transaction->setAmount('12.40');
$transaction->setCancelUrl('http://www.bing.com/');
$transaction->setCurrency('JPY');
$transaction->setExternalOrderNum(randomString());
$transaction->setReturnUrl('http://www.google.com/');
$transaction->setTax('1.24');

// Optional Customer Information
$customer = new Customer();
$customer->setFamilyName('山田');
$customer->setFamilyNameKana('ヤマダ');
$customer->setGivenName('太郎');
$customer->setGivenNameKana('タロウ');
$transaction->setCustomer($customer);

// Optional Address Information
$address = new Address();
$address->setCountry('jp');
$address->setPostalCode('123-4567');
$address->setState('AA');
$address->setCity('Tokyo');
$address->setStreetAddress('123 Sesame Street');
$address->setExtendedAddress('Suite ABC');
$address->setPhone('999-999-9999');
$customer->setBillingAddress($address);


$create_transaction = new CreateTransaction('en'); // 'ja' = Japanese, 'en' = English
$create_transaction->sandboxMode();
$url = $create_transaction->getSignedUrl($transaction);

echo $url;

function randomString($length = 10)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
