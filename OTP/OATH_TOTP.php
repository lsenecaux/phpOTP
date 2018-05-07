<?php
namespace OTP;

/*
 * TOTP CLASS
 *
 */
final class OATH_TOTP extends OATH_OTP
{
  public function __construct($Secret)
  {
      parent::__construct($Secret);
  }

  public function GetCurrentOTP()
  {
    $this->_counter = time() / 30;
    return parent::GetCurrentOTP();
  }
}
