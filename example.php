<?php

// See https://github.com/lsenecaux/phpCrypto
require_once 'Cryptography/HashAlgorithm.php';
require_once 'Cryptography/HMAC.php';

require_once 'OTP/Base32.php';
require_once 'OTP/OTP.php';
require_once 'OTP/OATH_OTP.php';
require_once 'OTP/OATH_HOTP.php';
require_once 'OTP/OATH_TOTP.php';

// Create a key >= 80 bits
$key = \OTP\Base32::Encode(openssl_random_pseudo_bytes(10));

// Create a new HMAC based OTP (RFC 4226)
$hotp = new \OTP\OATH_HOTP($key);
echo $hotp->GetCurrentOTP();

// Create a new Time based OTP (RFC 6238)
$totp = new \OTP\OATH_TOTP($key);
echo $totp->GetCurrentOTP();
