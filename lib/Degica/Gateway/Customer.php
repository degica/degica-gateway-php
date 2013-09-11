<?php

namespace Degica\Gateway;

class Customer {
    private $first_name;
    private $first_name_kana;
    private $last_name;
    private $last_name_kana;

    public function getFirstName() {
        return $this->first_name;
    }

    public function setFirstName($first_name) {
        $this->first_name = $first_name;
    }

    public function getFirstNameKana() {
        return $this->first_name_kana;
    }

    public function setFirstNameKana($first_name_kana) {
        $this->first_name_kana = $first_name_kana;
    }

    public function getLastName() {
        return $this->last_name;
    }

    public function setLastName($last_name) {
        $this->last_name = $last_name;
    }

    public function getLastNameKana() {
        return $this->last_name_kana;
    }

    public function setLastNameKana($last_name_kana) {
        $this->last_name_kana = $last_name_kana;
    }
}
