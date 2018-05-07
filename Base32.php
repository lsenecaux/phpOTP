<?php
namespace OTP;

final class Base32
{
  private static $map = array(
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', //  7
    'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 15
    'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', // 23
    'Y', 'Z', '2', '3', '4', '5', '6', '7', // 31
    '='  // padding char
    );

  private static $flippedMap = array(
    'A'=>'0', 'B'=>'1', 'C'=>'2', 'D'=>'3', 'E'=>'4', 'F'=>'5', 'G'=>'6', 'H'=>'7',
    'I'=>'8', 'J'=>'9', 'K'=>'10', 'L'=>'11', 'M'=>'12', 'N'=>'13', 'O'=>'14', 'P'=>'15',
    'Q'=>'16', 'R'=>'17', 'S'=>'18', 'T'=>'19', 'U'=>'20', 'V'=>'21', 'W'=>'22', 'X'=>'23',
    'Y'=>'24', 'Z'=>'25', '2'=>'26', '3'=>'27', '4'=>'28', '5'=>'29', '6'=>'30', '7'=>'31'
    );

  /*
   * Encode input string
   * @param $input string
   * @param $padding boolean
   * @return string
   */
  public static function Encode($input, $padding = true)
  {
    if (empty($input))
      return '';

    $input = str_split($input);
    $binaryString = '';

    for ($i = 0; $i < count($input); $i++)
      $binaryString .= str_pad(base_convert(ord($input[$i]), 10, 2), 8, '0', STR_PAD_LEFT);

    $fiveBitBinaryArray = str_split($binaryString, 5);
    $base32 = '';
    $i=0;

    while ($i < count($fiveBitBinaryArray))
    {
      $base32 .= self::$map[base_convert(str_pad($fiveBitBinaryArray[$i], 5,'0'), 2, 10)];
      $i++;
    }

    if($padding && ($x = strlen($binaryString) % 40) != 0)
    {
      if ($x == 8) $base32 .= str_repeat(self::$map[32], 6);
      else if ($x == 16) $base32 .= str_repeat(self::$map[32], 4);
      else if ($x == 24) $base32 .= str_repeat(self::$map[32], 3);
      else if ($x == 32) $base32 .= self::$map[32];
    }

    return $base32;
  }

  /*
   * Decode input string
   * @param $input string
   * @return string
   * @throw \Exception
   */
  public static function Decode($input)
  {
    if (empty($input))
      throw new \Exception('Input string is empty !');

    $paddingCharCount = substr_count($input, self::$map[32]);
    $allowedValues = array(6,4,3,1,0);

    if (!in_array($paddingCharCount, $allowedValues))
      throw new \Exception('Invalid padding !');

    for ($i = 0; $i < 4; $i++)
      if ($paddingCharCount == $allowedValues[$i] && substr($input, -($allowedValues[$i])) != str_repeat(self::$map[32], $allowedValues[$i]))
        throw new \Exception('Invalid padding !');

    $input = str_replace('=', '', $input);
    $input = str_split($input);
    $binaryString = '';

    for ($i = 0; $i < count($input); $i += 8)
    {
      $x = '';

      if(!in_array($input[$i], self::$map))
        throw new \Exception('Input string contains invalid characters !');

      for($j=0; $j < 8; $j++)
        $x .= str_pad(base_convert(@self::$flippedMap[@$input[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);

      $eightBits = str_split($x, 8);

      for($z = 0; $z < count($eightBits); $z++)
        $binaryString .= ( ($y = chr(base_convert($eightBits[$z], 2, 10))) || ord($y) == 48 ) ? $y : '';
    }

    return $binaryString;
  }
}
