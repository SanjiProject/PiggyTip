<?php
namespace App\Controllers; use App\Lib\Helpers; use App\Lib\Validator; use App\Models\User; use App\Models\Link; use App\Models\Wallet; use App\Models\Analytics;

class DashboardController{
  private static function currentUser(): array { $u=User::findById((int)($_SESSION['user_id']??0)); if(!$u) Helpers::redirect('/login'); return $u; }
  private static function paymentCount(int $uid): int { $pdo=\App\DB::pdo(); $s1=$pdo->prepare('SELECT COUNT(*) FROM links WHERE user_id=?'); $s1->execute([$uid]); $links=(int)$s1->fetchColumn(); $s2=$pdo->prepare('SELECT COUNT(*) FROM wallets WHERE user_id=?'); $s2->execute([$uid]); $wallets=(int)$s2->fetchColumn(); return $links+$wallets; }
  public static function index(): string { $user=self::currentUser(); $summary=Analytics::summaryCounters((int)$user['id'],7); ob_start(); require APP_BASE_PATH.'/app/Views/dashboard/index.php'; $content=ob_get_clean(); $pageTitle='Dashboard'; ob_start(); require APP_BASE_PATH.'/app/Views/layouts/main.php'; return ob_get_clean(); }
  public static function links(): string { \App\Lib\Helpers::redirect('/dashboard/payments'); return ''; }
  public static function wallets(): string { \App\Lib\Helpers::redirect('/dashboard/payments'); return ''; }
  public static function profile(): string { $user=self::currentUser(); ob_start(); require APP_BASE_PATH.'/app/Views/dashboard/profile.php'; $content=ob_get_clean(); $pageTitle='Profile'; ob_start(); require APP_BASE_PATH.'/app/Views/layouts/main.php'; return ob_get_clean(); }
  public static function quickAdd(): string { $user=self::currentUser(); ob_start(); require APP_BASE_PATH.'/app/Views/dashboard/quick_add.php'; $content=ob_get_clean(); $pageTitle='Quick add'; ob_start(); require APP_BASE_PATH.'/app/Views/layouts/main.php'; return ob_get_clean(); }
  public static function payments(): string { $user=self::currentUser(); $links=Link::forUser((int)$user['id']); $wallets=Wallet::forUser((int)$user['id']); ob_start(); require APP_BASE_PATH.'/app/Views/dashboard/payments.php'; $content=ob_get_clean(); $pageTitle='Payments'; ob_start(); require APP_BASE_PATH.'/app/Views/layouts/main.php'; return ob_get_clean(); }
  public static function createLink(): void { $user=self::currentUser(); $label=trim($_POST['label']??''); $url=trim($_POST['url']??''); $type=$_POST['type']??'custom'; $visible=isset($_POST['is_visible'])?1:0; if(!Validator::nonEmpty($label,1,80)||!Validator::url($url)){ $_SESSION['flash_error']='Invalid link'; Helpers::redirect('/dashboard/payments'); } if(self::paymentCount((int)$user['id'])>=5){ $_SESSION['flash_error']='You already reached the max of 5 payments'; Helpers::redirect('/dashboard/payments'); } Link::create((int)$user['id'],['label'=>$label,'url'=>$url,'type'=>$type,'is_visible'=>$visible]); Helpers::redirect('/dashboard/payments'); }
  public static function updateLink(int $id): void { $user=self::currentUser(); $label=trim($_POST['label']??''); $url=trim($_POST['url']??''); $type=$_POST['type']??'custom'; $visible=isset($_POST['is_visible'])?1:0; Link::update($id,(int)$user['id'],['label'=>$label,'url'=>$url,'type'=>$type,'is_visible'=>$visible]); if(isset($_POST['set_primary'])){ $user['primary_link_id']=$id; User::updateProfile((int)$user['id'],['display_name'=>$user['display_name'],'bio'=>$user['bio'],'username'=>$user['username'],'slug'=>$user['slug'],'primary_link_id'=>$id]); } Helpers::redirect('/dashboard/payments'); }
  public static function deleteLink(int $id): void { $user=self::currentUser(); Link::delete($id,(int)$user['id']); if((int)($user['primary_link_id']??0)===$id){ User::updateProfile((int)$user['id'],['display_name'=>$user['display_name'],'bio'=>$user['bio'],'username'=>$user['username'],'slug'=>$user['slug'],'primary_link_id'=>null]); } Helpers::redirect('/dashboard/payments'); }
  public static function createWallet(): void { $user=self::currentUser(); $label=trim($_POST['label']??''); $chain=trim($_POST['chain']??'TRON'); $address=trim($_POST['address']??''); $payment_uri=trim($_POST['payment_uri']??''); $visible=isset($_POST['is_visible'])?1:0; if(!Validator::nonEmpty($label,2,60)||!Validator::nonEmpty($address,6,120)){ $_SESSION['flash_error']='Invalid wallet'; Helpers::redirect('/dashboard/payments'); } if(self::paymentCount((int)$user['id'])>=5){ $_SESSION['flash_error']='You already reached the max of 5 payments'; Helpers::redirect('/dashboard/payments'); } Wallet::create((int)$user['id'],compact('label','chain','address','payment_uri')+['is_visible'=>$visible]); Helpers::redirect('/dashboard/payments'); }
  public static function updateWallet(int $id): void { $user=self::currentUser(); $label=trim($_POST['label']??''); $chain=trim($_POST['chain']??'TRON'); $address=trim($_POST['address']??''); $payment_uri=trim($_POST['payment_uri']??''); $visible=isset($_POST['is_visible'])?1:0; Wallet::update($id,(int)$user['id'],compact('label','chain','address','payment_uri')+['is_visible'=>$visible]); Helpers::redirect('/dashboard/payments'); }
  public static function deleteWallet(int $id): void { $user=self::currentUser(); Wallet::delete($id,(int)$user['id']); Helpers::redirect('/dashboard/payments'); }
  public static function bulkAddPayments(): void { $user=self::currentUser(); $rows = $_POST['rows'] ?? []; $created=0; $current=self::paymentCount((int)$user['id']); $limit=5; $available=max(0,$limit-$current); $skipped=0; foreach ($rows as $row) {
      if (empty($row['enabled'])) continue; $kind = $row['kind'] ?? 'wallet';
      if ($available<=0) { $skipped++; continue; }
      if ($kind === 'wallet') {
        $label=trim($row['label']??'');
        $chain=trim($row['chain']??'');
        $address=trim(($row['address']??'')!=='' ? $row['address'] : ($row['url']??''));
        $payment_uri=trim($row['payment_uri']??'');
        $visible=!empty($row['is_visible'])?1:0;
        if ($label!=='' && $address!=='') { Wallet::create((int)$user['id'],compact('label','chain','address','payment_uri')+['is_visible'=>$visible]); $created++; $available--; }
      } else if ($kind === 'link') {
        $label=trim($row['label']??''); $url=trim($row['url']??''); $type=$row['type']??'custom'; $visible=!empty($row['is_visible'])?1:0;
        if ($label!=='' && $url!=='') { Link::create((int)$user['id'],['label'=>$label,'url'=>$url,'type'=>$type,'is_visible'=>$visible]); $created++; $available--; }
      }
    }
    $_SESSION['flash_success'] = $created>0? ("Added $created item(s)" . ($skipped>0? '  Reached max of 5 payments.':'')) : 'You already reached the max of 5 payments';
    Helpers::redirect('/dashboard/payments');
  }
}
