<?php

namespace Degica\Gateway\Request;

class CreateTransaction {
    const SANDBOX = 1;
    const PRODUCTION = 2;

    private $locale;
    private $environment = self::PRODUCTION;
    public $time;

    public function __construct($locale = 'ja') {
        $this->locale = $locale;
        $this->time = time();
    }

    public function sandboxMode()
    {
        $this->environment = self::SANDBOX;
    }

    public function productionMode()
    {
        $this->environment = self::PRODUCTION;
    }

    public function getSignedUrl(\Degica\Gateway\Transaction $transaction) {
        if (!$transaction->isValid())
        {
            throw new InvalidTransactionException();
        }
        $secret_key = $transaction->getMerchant()->getApiKey();
        $endpoint = "/{$this->locale}/api/{$transaction->getMerchant()->getMerchantSlug()}/transactions/{$transaction->getPaymentMethod()}/new";

        $params = array(
            "transaction[amount]={$transaction->getAmount()}",
            "transaction[currency]={$transaction->getCurrency()}",
            "transaction[external_order_num]={$transaction->getExternalOrderNum()}",
            "transaction[return_url]={$transaction->getReturnUrl()}",
            "transaction[cancel_url]={$transaction->getCancelUrl()}",
            "transaction[tax]={$transaction->getTax()}",
            "timestamp=" . $this->time,
        );

        $customer = $transaction->getCustomer();
        if ($customer) {
            $customer_fields = array(
                'given_name' => $customer->getGivenName(),
                'given_name_kana' => $customer->getGivenNameKana(),
                'family_name' => $customer->getFamilyName(),
                'family_name_kana' => $customer->getFamilyNameKana(),
            );
            foreach ($customer_fields as $key => $val) {
                if ($val) {
                    $params[] = "transaction[customer][$key]=$val";
                }
            }

            $address = $customer->getBillingAddress();
            if ($address) {
                $address_fields = array(
                    'country' => strtoupper($address->getCountry()),
                    'zipcode' => $address->getPostalCode(),
                    'state' => $address->getState(),
                    'city' => $address->getCity(),
                    'street_address' => $address->getStreetAddress(),
                    'extended_address' => $address->getExtendedAddress(),
                    'phone' => $address->getPhone(),
                );
                foreach ($address_fields as $key => $val) {
                    if ($val) {
                        $params[] = "transaction[customer][billing_address][$key]=$val";
                    }
                }
            }
        }
        sort($params);

        $query_string = urldecode(implode('&', $params));
        $url = $endpoint . '?' . $query_string;

        $hmac = hash_hmac('sha256', $url, $secret_key);

        return "https://{$this->host()}{$url}&hmac={$hmac}";
    }

    private function host()
    {
        if ($this->environment == self::SANDBOX)
        {
            return "gateway-sandbox.degica.com";
        }
        elseif ($this->environment == self::PRODUCTION)
        {
            return "gateway.degica.com";
        }
    }
}
