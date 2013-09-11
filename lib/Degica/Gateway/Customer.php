<?php

namespace Degica\Gateway;

class Customer {
    private $given_name;
    private $given_name_kana;
    private $family_name;
    private $family_name_kana;

    public function getGivenName() {
        return $this->given_name;
    }

    public function setGivenName($given_name) {
        $this->given_name = $given_name;
    }

    public function getGivenNameKana() {
        return $this->given_name_kana;
    }

    public function setGivenNameKana($given_name_kana) {
        $this->given_name_kana = $given_name_kana;
    }

    public function getFamilyName() {
        return $this->family_name;
    }

    public function setFamilyName($family_name) {
        $this->family_name = $family_name;
    }

    public function getFamilyNameKana() {
        return $this->family_name_kana;
    }

    public function setFamilyNameKana($family_name_kana) {
        $this->family_name_kana = $family_name_kana;
    }
}
