<?php

namespace Degica\Gateway;

class Transaction {
    private $merchant;
    private $payment_method = PaymentMethod::CREDIT_CARD;
    private $amount;
    private $cancel_url;
    private $currency = 'JPY';
    private $external_order_num;
    private $return_url;
    private $tax;
    private $customer;

    public function __construct(Merchant $merchant) {
        $this->merchant = $merchant;
    }

    public function getMerchant() {
        return $this->merchant;
    }

    public function setMerchant(Merchant $merchant) {
        $this->merchant = $merchant;
    }

    public function getPaymentMethod() {
        return $this->payment_method;
    }

    public function setPaymentMethod($payment_method) {
        if (!in_array($payment_method, PaymentMethod::all())) {
            throw new \InvalidArgumentException("Invalid Payment Method");
        }
        $this->payment_method = $payment_method;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        if (!is_numeric($amount)) {
            throw new \InvalidArgumentException("Amount must be numeric, got `$amount`.");
        }
        $this->amount = $amount;
    }

    public function getCancelUrl() {
        return $this->cancel_url;
    }

    public function setCancelUrl($cancel_url) {
        if (!filter_var($cancel_url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Cancel URL was not a valid URL, got `$cancel_url`");
        }
        $this->cancel_url = $cancel_url;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function setCurrency($currency) {
        if ($currency != 'JPY') {
            throw new \InvalidArgumentException("Unsupported currency `$currency`.");
        }
        $this->currency = $currency;
    }

    public function getExternalOrderNum() {
        return $this->external_order_num;
    }

    public function setExternalOrderNum($external_order_num) {
        if (strlen($external_order_num) > 255) {
            throw new \InvalidArgumentException("External Order Number is greater than 255 characters");
        }
        $this->external_order_num = $external_order_num;
    }

    public function getReturnUrl() {
        return $this->return_url;
    }

    public function setReturnUrl($return_url) {
        if (!filter_var($return_url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Return URL was not a valid URL, got `$return_url`");
        }
        $this->return_url = $return_url;
    }

    public function getTax() {
        return $this->tax;
    }

    public function setTax($tax) {
        if (!is_numeric($tax)) {
            throw new \InvalidArgumentException("Tax must be numeric, got `$tax`.");
        }
        $this->tax = $tax;
    }

    public function getCustomer() {
        return $this->customer;
    }

    public function setCustomer(Customer $customer) {
        $this->customer = $customer;
    }

    public function isValid() {
        $errors = array();
        if (empty($this->merchant)) { $errors[] = "merchant is empty"; }
        if (empty($this->payment_method)) { $errors[] = "payment_method is empty"; }
        if (empty($this->amount)) { $errors[] = "amount is empty"; }
        if (empty($this->cancel_url)) { $errors[] = "cancel_url is empty"; }
        if (empty($this->currency)) { $errors[] = "currency is empty"; }
        if (empty($this->external_order_num)) { $errors[] = "external_order_num is empty"; }
        if (empty($this->return_url)) { $errors[] = "return_url is empty"; }
        if (empty($this->tax)) { $errors[] = "tax is empty"; }
        return empty($errors);
    }
}
