<?php

require __DIR__ . '/../vendor/autoload.php';

use Degica\Gateway\Merchant;
use Degica\Gateway\Callback\Request;

$merchant = new Merchant();
$merchant->setMerchantSlug('degica-mart');
$merchant->setApiKey('42f89dc68c192645c2a752e907fcf648c2ff6782f7c18dffa0b6decd3ec7b030');

$request = new Request($merchant, $_SERVER['SCRIPT_NAME'], $_REQUEST);;
$transaction = $request->getTransaction();
var_dump($transaction);
