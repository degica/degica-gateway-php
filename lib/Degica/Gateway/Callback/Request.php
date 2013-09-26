<?php

namespace Degica\Gateway\Callback;

use Degica\Gateway\Merchant;

class Request {
    private $script_name;
    private $params;
    private $merchant;
    public $time;

    public function __construct(Merchant $merchant, $script_name, $params) {
        $this->script_name = $script_name;
        $this->params = $params;
        $this->merchant = $merchant;
        $this->time = time();
    }

    public function getTransaction() {
        $this->verifyHmac();
        $this->verifyTimestamp();

        $transaction = new Transaction();
        $transaction->setAmount($this->params['transaction']['amount']);
        $transaction->setCurrency($this->params['transaction']['currency']);
        $transaction->setExternalOrderNum($this->params['transaction']['external_order_num']);
        $transaction->setPaymentMethod($this->params['transaction']['payment_method']);
        $transaction->setStatus($this->params['transaction']['status']);
        $transaction->setTax($this->params['transaction']['tax']);
        $transaction->setUuid($this->params['transaction']['uuid']);
        if (isset($this->params['transaction']['additional_information'])) {
            $transaction->setAdditionalInformation($this->params['transaction']['additional_information']);
        }
        return $transaction;
    }

    private function hmacString() {
        $params = array();
        $params[] = "timestamp=" . $this->params['timestamp'];
        if (isset($this->params['transaction']['additional_information'])) {
            foreach ($this->params['transaction']['additional_information'] as $key => $val) {
                $params[] = "transaction[additional_information][$key]=$val";
            }
        }
        $params[] = "transaction[amount]=" . $this->params['transaction']['amount'];
        $params[] = "transaction[currency]=" . $this->params['transaction']['currency'];
        $params[] = "transaction[external_order_num]=" . $this->params['transaction']['external_order_num'];
        $params[] = "transaction[payment_method]=" . $this->params['transaction']['payment_method'];
        $params[] = "transaction[status]=" . $this->params['transaction']['status'];
        $params[] = "transaction[tax]=" . $this->params['transaction']['tax'];
        $params[] = "transaction[uuid]=" . $this->params['transaction']['uuid'];
        sort($params);
        return $this->script_name . "?" . implode('&', $params);
    }

    private function verifyHmac() {
        $hmac = hash_hmac('sha256', $this->hmacString(), $this->merchant->getApiKey());
        if ($this->params['hmac'] != $hmac) {
            throw new InvalidHmacException();
        }
    }

    private function verifyTimestamp() {
        if (abs($this->time - $this->params['timestamp']) > 360) {
            throw new ExpiredRequestException();
        }
    }
}
