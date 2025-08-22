<?php
namespace App\Lib;

class Validator{
  public static function email(string $v): bool { return filter_var($v, FILTER_VALIDATE_EMAIL)!==false; }
  public static function url(string $v): bool { return filter_var($v, FILTER_VALIDATE_URL)!==false; }
  public static function nonEmpty(string $v,int $min=1,int $max=255): bool { $l=mb_strlen(trim($v)); return $l>=$min && $l<=$max; }
}
