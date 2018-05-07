<?php
namespace OTP;

/*
 * OTP INTERFACE
 *
 */
interface OTP
{
  public function __set($Property, $Value);
  public function __get($Property);
  public function GetCurrentOTP();
}
