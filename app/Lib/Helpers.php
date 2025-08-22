<?php
namespace App\Lib;

class Helpers
{
    public static function e(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
    public static function url(string $path = ''): string {
        $base = rtrim(\App\Config::appUrl(), '/');
        $p = ltrim($path, '/');
        if ($p === '' || $p === '/') { return $base; }
        // Always use clean URLs now that the server is configured for rewrites
        return $base . '/' . $p;
    }
    public static function redirect(string $path): void { header('Location: ' . self::url($path)); exit; }
    public static function json(array $data, int $status=200): void { http_response_code($status); header('Content-Type: application/json'); echo json_encode($data); exit; }
    public static function csrfToken(): string { if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token']=bin2hex(random_bytes(32)); return $_SESSION['csrf_token']; }
    public static function csrfField(): string { return '<input type="hidden" name="_token" value="'.self::e(self::csrfToken()).'">'; }
    public static function requireCsrf(): void {
        $token = $_POST['_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        $ok = is_string($token) && hash_equals($_SESSION['csrf_token']??'', $token);
        if(!$ok){ http_response_code(419); echo 'CSRF mismatch'; exit; }
    }
    public static function slugify(string $t): string { $t=preg_replace('~[\p{Z}\s]+~u','-',trim($t)); $t=preg_replace('~[^\pL\pN_-]+~u','',$t); return mb_strtolower(trim($t,'-'))?:'user'; }
    public static function clientIpBinary(): ?string { $ip=$_SERVER['REMOTE_ADDR']??null; if(!$ip) return null; $bin=@inet_pton($ip); return $bin===false?null:$bin; }
    public static function middleTruncate(string $v,int $m=20): string { if(mb_strlen($v)<= $m) return $v; $k=max(4,(int)floor(($m-3)/2)); return mb_substr($v,0,$k).'...'.mb_substr($v,-$k); }

    // UI helpers
    public static function coinDetect(?string $label, ?string $chain): array {
        $labelUpper = mb_strtoupper($label ?? '');
        $chainUpper = mb_strtoupper($chain ?? '');
        $symbol = null;
        foreach (['USDT','BTC','ETH','BNB','SOL','TRX'] as $sym) {
            if (str_contains($labelUpper, $sym)) { $symbol = $sym; break; }
        }
        if ($symbol === null) {
            $symbol = match (true) {
                str_contains($chainUpper, 'TRON') => 'TRX',
                str_contains($chainUpper, 'ETH') => 'ETH',
                str_contains($chainUpper, 'BSC') || str_contains($chainUpper, 'BNB') => 'BNB',
                str_contains($chainUpper, 'SOL') => 'SOL',
                default => null,
            };
        }
        $network = null;
        if ($symbol === 'USDT') {
            $network = match (true) {
                str_contains($labelUpper, 'TRC') || str_contains($chainUpper, 'TRON') => 'TRC-20',
                str_contains($labelUpper, 'ERC') || str_contains($chainUpper, 'ETH') => 'ERC-20',
                str_contains($labelUpper, 'BEP') || str_contains($chainUpper, 'BSC') || str_contains($chainUpper, 'BNB') => 'BEP-20',
                str_contains($chainUpper, 'SOL') => 'SOL',
                default => null,
            };
        } else if ($symbol !== null) {
            $network = $chainUpper ?: $symbol;
        }
        return ['symbol' => $symbol, 'network' => $network];
    }

    public static function iconBadge(string $key, string $classes='inline-flex h-8 w-8 items-center justify-center rounded-full text-white text-xs font-bold'): string {
        $k = strtolower(trim($key));
        $bg = match ($k) {
            'usdt' => '#26A17B',
            'btc', 'bitcoin' => '#F7931A',
            'eth', 'ethereum' => '#627EEA',
            'bnb', 'binance' => '#F3BA2F',
            'sol', 'solana' => '#14F195',
            'trx', 'tron' => '#EB0029',
            'paypal' => '#003087',
            'link', 'custom', 'other' => '#6B7280',
            default => '#6B7280',
        };
        $text = match ($k) {
            'bitcoin' => 'BTC',
            'ethereum' => 'ETH',
            'binance' => 'BNB',
            'solana' => 'SOL',
            'tron' => 'TRX',
            default => strtoupper(substr($k, 0, 4)) ?: 'X',
        };
        // Ensure short text for known brands
        if (in_array($k, ['usdt','btc','eth','bnb','sol','trx','paypal'], true)) { $text = strtoupper($k === 'paypal' ? 'P' : $k); $text = $k==='paypal' ? 'P' : $text; if ($k!=='paypal') { $text = strtoupper($k); } if (strlen($text) > 4) { $text = substr($text,0,4);} }
        $style = 'background: '.$bg.';';
        return '<span class="'.self::e($classes).'" style="'.$style.'">'.self::e($text).'</span>';
    }

    public static function brandIconImg(string $key, string $classes='h-5 w-5'): string {
        $k = strtolower(trim($key));
        $map = [
            'usdt' => ['tether', '26A17B'],
            'tether' => ['tether', '26A17B'],
            'btc' => ['bitcoin', 'F7931A'],
            'bitcoin' => ['bitcoin', 'F7931A'],
            'eth' => ['ethereum', '627EEA'],
            'ethereum' => ['ethereum', '627EEA'],
            'bnb' => ['binance', 'F3BA2F'],
            'binance' => ['binance', 'F3BA2F'],
            'sol' => ['solana', '00FFA3'],
            'solana' => ['solana', '00FFA3'],
            'trx' => ['tron', 'EF002A'],
            'tron' => ['tron', 'EF002A'],
            'ltc' => ['litecoin', '345D9D'],
            'litecoin' => ['litecoin', '345D9D'],
            'doge' => ['dogecoin', 'C2A633'],
            'dogecoin' => ['dogecoin', 'C2A633'],
            'xrp' => ['ripple', '0085FF'],
            'ripple' => ['ripple', '0085FF'],
            'ada' => ['cardano', '0033AD'],
            'cardano' => ['cardano', '0033AD'],
            'matic' => ['polygon', '8247E5'],
            'polygon' => ['polygon', '8247E5'],
            'cashapp' => ['cashapp', '00C244'],
            'patreon' => ['patreon', 'FF424D'],
            'buymeacoffee' => ['buymeacoffee', 'FFDD00'],
            'kofi' => ['kofi', '13C3FF'],
            'stripe' => ['stripe', '635BFF'],
            'paypal' => ['paypal', '003087'],
        ];
        $entry = $map[$k] ?? null;
        if (!$entry) {
            // Fallback to a generic link icon from heroicons (inline svg)
            return '<svg class="'.self::e($classes).'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6h2.25A3.75 3.75 0 0 1 19.5 9.75v0A3.75 3.75 0 0 1 15.75 13.5H14m-4 0H8.25A3.75 3.75 0 0 1 4.5 9.75v0A3.75 3.75 0 0 1 8.25 6H10m-1 6h6"/></svg>';
        }
        [$slug, $color] = $entry;
        // Prefer CryptoLogos for TRON which is more reliable in some environments
        if ($slug === 'tron') {
            // Use query-route to ensure it goes through front controller even without rewrite rules
            $src = \App\Lib\Helpers::url('/index.php?route=/icon/tron.svg');
            $fallback = \App\Lib\Helpers::url('/assets/fallbacks/tron.svg');
        } else {
            $src = 'https://cdn.simpleicons.org/'.$slug.'/'.rawurlencode($color);
            $fallback = 'https://api.iconify.design/simple-icons:'.$slug.'.svg?color=%23'.rawurlencode($color);
        }
        $alt = ucfirst($k);
        return '<img src="'.self::e($src).'" onerror="this.onerror=null;this.src=\''.self::e($fallback).'\'" alt="'.self::e($alt).'" class="'.self::e($classes).'" loading="lazy" width="20" height="20" />';
    }
}
