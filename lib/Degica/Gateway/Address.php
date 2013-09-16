<?php

namespace Degica\Gateway;

class Address {
    private $country;
    private $postal_code;
    private $state;
    private $city;
    private $street_address;
    private $extended_address;
    private $phone;

    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function getPostalCode() {
        return $this->postal_code;
    }

    public function setPostalCode($postal_code) {
        $this->postal_code = $postal_code;
    }

    public function getZipCode() {
        return $this->getPostalCode();
    }

    public function setZipCode($zip_code) {
        $this->setPostalCode($zip_code);
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getStreetAddress() {
        return $this->street_address;
    }

    public function setStreetAddress($street_address) {
        $this->street_address = $street_address;
    }

    public function getExtendedAddress() {
        return $this->extended_address;
    }

    public function setExtendedAddress($extended_address) {
        $this->extended_address = $extended_address;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

}
