<?php
namespace App\Middleware;

use App\Lib\Helpers;

class Auth{
  public static function check(): bool { return !empty($_SESSION['user_id']); }
  public static function requireLogin(): void { if(!self::check()) Helpers::redirect('/login'); }
}
