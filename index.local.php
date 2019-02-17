<?php
require_once 'vendor/autoload.php';

define("ROOTDIR", __DIR__);
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/local.env');

require_once 'index-common.php';
