<?php

namespace Degica\Gateway;

class Merchant {

    private $merchant_slug;
    private $api_key;

    public function getMerchantSlug()
    {
        return $this->merchant_slug;
    }

    public function setMerchantSlug($merchant_slug)
    {
        $this->merchant_slug = $merchant_slug;
    }

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
    }
}
