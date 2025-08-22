<?php
namespace App\Middleware;

use App\Lib\Helpers;

class Csrf{ public static function field(): string { return Helpers::csrfField(); } public static function verify(): void { Helpers::requireCsrf(); } }
