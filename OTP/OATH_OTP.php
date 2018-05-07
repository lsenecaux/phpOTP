<?php
namespace OTP;

/*
 * OTP CLASS
 *
 */
abstract class OATH_OTP implements OTP
{
  protected $_counter;
  protected $_properties;
  protected $_hash;

  public function __construct($Secret)
  {
    $this->_properties = array(
      'window'  => 1,
      'digits'  => 6,
    );

    $this->_hash = new \Cryptography\HMAC();
    $this->_hash->Algorithm = 'sha1';

    try
    {
      $this->_hash->Key = Base32::Decode($Secret);
    }
    catch (\Exception $e)
    {
      throw $e;
    }
  }

  public function __set($Property, $Value)
  {
    if (!array_key_exists(strtolower($Property), $this->_properties))
      throw new \Exception(sprintf('%s is not a valid property', $Property));

    $this->_properties[strtolower($Property)] = $Value;
  }

  public function __get($Property)
  {
    if (!array_key_exists(strtolower($Property), $this->_properties))
      throw new \Exception(sprintf('%s is not a valid property', $Property));

    return $this->_properties[strtolower($Property)];
  }

  public function GetCurrentOTP()
  {
    $data = pack('NNC*', $this->_counter >> 32, $this->_counter & 0xFFFFFFFF);
    $data = str_pad($data, 8, chr(0), STR_PAD_LEFT);
    $hash = $this->_hash->ComputeHash($data, FALSE);
    $offset = 2 * hexdec(substr($hash, strlen($hash) -1 , 1));
    $binary = hexdec(substr($hash, $offset, 8)) & 0x7FFFFFFF;
    $result = $binary % pow(10, $this->Digits);
    $otp = str_pad($result, $this->Digits, '0', STR_PAD_LEFT);

    return $otp;
  }
}
