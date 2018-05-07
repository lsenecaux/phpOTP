<?php
namespace OTP;

/*
 * HOTP CLASS
 *
 */
final class OATH_HOTP extends OATH_OTP
{
  public function __construct($Secret, $Counter = 1)
  {
    parent::__construct($Secret);
    $this->_counter = $Counter;
  }

  public function GetCurrentOTP()
  {
    $otp = parent::GetCurrentOTP();
    $this->_counter++;

    return $otp;
  }
}
