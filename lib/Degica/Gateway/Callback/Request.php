<?php

namespace Degica\Gateway\Callback;

use Degica\Gateway\Merchant;

class Request {
    private $script_name;
    private $params;
    private $merchant;

    public function __construct(Merchant $merchant, $script_name, $params) {
        $this->script_name = $script_name;
        $this->params = $params;
        $this->merchant = $merchant;

        $this->verifyHmac();
        $this->verifyTimestamp();
    }

    public function getTransaction() {
        $transaction = new Transaction();
        $transaction->setAmount($this->params['transaction']['amount']);
        $transaction->setCurrency($this->params['transaction']['currency']);
        $transaction->setExternalOrderNum($this->params['transaction']['external_order_num']);
        $transaction->setPaymentMethod($this->params['transaction']['payment_method']);
        $transaction->setStatus($this->params['transaction']['status']);
        $transaction->setTax($this->params['transaction']['tax']);
        $transaction->setUuid($this->params['transaction']['uuid']);
        $transaction->setAdditionalInformation($this->params['transaction']['additional_information']);
        return $transaction;
    }

    private function verifyHmac() {
        // TODO -- Implement
    }

    private function verifyTimestamp() {
        if (abs(time() - $this->params['timestamp']) > 360) {
            throw new ExpiredRequestException();
        }
    }
}
