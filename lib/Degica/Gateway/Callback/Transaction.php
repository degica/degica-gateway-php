<?php

namespace Degica\Gateway\Callback;

class Transaction {
    private $additional_information;
    private $amount;
    private $currency;
    private $external_order_num;
    private $payment_method;
    private $status;
    private $tax;
    private $uuid;

    public function getAdditionalInformation() {
        return $this->additional_information;
    }
    public function setAdditionalInformation($additional_information) {
        $this->additional_information = $additional_information;
    }

    public function getAmount() {
        return $this->amount;
    }
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function getCurrency() {
        return $this->currency;
    }
    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    public function getExternalOrderNum() {
        return $this->external_order_num;
    }
    public function setExternalOrderNum($external_order_num) {
        $this->external_order_num = $external_order_num;
    }

    public function getPaymentMethod() {
        return $this->payment_method;
    }
    public function setPaymentMethod($payment_method) {
        $this->payment_method = $payment_method;
    }

    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $this->status = $status;
    }

    public function getTax() {
        return $this->tax;
    }
    public function setTax($tax) {
        $this->tax = $tax;
    }

    public function getUuid() {
        return $this->uuid;
    }
    public function setUuid($uuid) {
        $this->uuid = $uuid;
    }

}
