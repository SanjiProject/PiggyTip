<?php
namespace App\Controllers; use App\Lib\Helpers; use App\Models\User; use App\Models\Link; use App\Models\Wallet; use App\Models\Analytics;

class PageController{
  public static function landing(): string { $pageTitle='PiggyTip.me - Collect Support with Adorable PiggyTip Design'; $sponsors=[]; $jsonPath=APP_BASE_PATH.'/public/assets/sponsors.json'; if(is_file($jsonPath)){ $raw=@file_get_contents($jsonPath); $data=json_decode($raw,true); if(is_array($data)) $sponsors=$data; } ob_start(); ?>
  <section class="relative overflow-hidden">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
      <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-primary-500/10 blur-3xl"></div>
      <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-emerald-500/10 blur-3xl"></div>
    </div>
    <div class="container mx-auto px-4 py-10 md:py-14 lg:py-16 grid lg:grid-cols-2 gap-6 lg:gap-10 items-center">
      <div>
        <img src="<?= Helpers::e(Helpers::url('/img/swag.webp')) ?>" alt="Logo" class="block mx-auto h-24 w-24 md:h-28 md:w-28 object-contain shadow-soft mb-3 md:mb-5" />
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight">Collect Support with Adorable PiggyTip Design</h1>
        <p class="mt-3 md:mt-4 text-lg text-gray-600 dark:text-gray-300">Share one link, get all the love — PayPal, Binance Pay, crypto. We track the buzz for you!💌🐷</p>
         <div class="mt-4 md:mt-5 flex flex-wrap gap-3">
          <a href="<?= Helpers::e(Helpers::url('/register')) ?>" class="px-5 py-3 rounded-xl bg-primary-600 text-white hover:bg-primary-700 shadow-soft">✨Start free✨</a>
          <a href="<?= Helpers::e(Helpers::url('/login')) ?>" class="px-5 py-3 rounded-xl border border-gray-300 dark:border-gray-700 text-white/90 hover:text-white">Login</a>
        </div>
        <ul class="mt-5 md:mt-6 grid sm:grid-cols-2 gap-2.5 md:gap-3 text-sm">
          <li class="flex items-center gap-3"><span class="h-6 w-6 inline-flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-200">✓</span> Add up to 5 payments fast</li>
          <li class="flex items-center gap-3"><span class="h-6 w-6 inline-flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-200">✓</span> No payment processing</li>
          <li class="flex items-center gap-3"><span class="h-6 w-6 inline-flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-200">✓</span> Cute & Adorable Piggy Design</li>
          <li class="flex items-center gap-3"><span class="h-6 w-6 inline-flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-200">✓</span> 100% Free</li>
        </ul>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-soft border border-gray-200 dark:border-gray-700 p-5 md:p-6">
        <div class="text-sm text-gray-500 mb-2">Preview</div>
        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 p-4 md:p-5 text-center">
          <img src="<?= Helpers::e(Helpers::url('/img/profile.webp')) ?>" alt="Sample profile" class="mx-auto h-16 w-16 rounded-full object-cover shadow" />
          <div class="mt-2 font-medium">CoolPiggyName</div>
          <div class="mt-4 grid gap-2">
            <div class="py-3 rounded-2xl bg-primary-600 hover:bg-primary-700 btn-pink-black flex items-center justify-center gap-3">
              <?= Helpers::brandIconImg('paypal','h-5 w-5 rounded-full icon-white p-1') ?>
              <span>PayPal</span>
            </div>
            <div class="py-3 rounded-2xl bg-primary-600 hover:bg-primary-700 btn-pink-black flex items-center justify-center gap-3">
              <?= Helpers::brandIconImg('usdt','h-5 w-5 rounded-full icon-white p-1') ?>
              <span>USDT (TRC-20)</span>
            </div>
            <div class="py-3 rounded-2xl bg-primary-600 hover:bg-primary-700 btn-pink-black flex items-center justify-center gap-3">
              <?= Helpers::brandIconImg('bnb','h-5 w-5 rounded-full icon-white p-1') ?>
              <span>Binance Pay</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-semibold mb-4 text-center">Sponsorship</h2>
    <div class="mx-auto w-fit grid grid-cols-2 sm:grid-cols-4 gap-1 justify-items-center">
        <?php foreach (($sponsors ?? []) as $sp): $logo = (string)($sp['logo'] ?? ''); $src = str_starts_with($logo,'http')? $logo : Helpers::url(ltrim($logo,'/')); ?>
          <a href="<?= Helpers::e((string)($sp['url']??'#')) ?>" target="_blank" rel="noopener" class="sponsor-card flex items-center justify-center p-2 w-20 h-20 sm:w-24 sm:h-24">
            <img src="<?= Helpers::e($src) ?>" alt="<?= Helpers::e((string)($sp['name']??'')) ?>" class="max-h-[80%] max-w-[80%] object-contain" />
          </a>
        <?php endforeach; ?>
    </div>
  </section>
  <?php $content=ob_get_clean(); ob_start(); require APP_BASE_PATH.'/app/Views/layouts/main.php'; return ob_get_clean(); }
  public static function publicByUsername(string $username): string {
    $user = User::findByUsername($username);
    if (!$user) { $user = User::findBySlug($username); }
    if(!$user) return self::notFound();
    return self::renderPublic($user);
  }
  public static function publicBySlug(string $slug): string { return self::notFound(); }
  private static function renderPublic(array $user): string { Analytics::recordEvent((int)$user['id'], null, 'pageview', $_SERVER['HTTP_USER_AGENT']??null, Helpers::clientIpBinary()); $links=Link::visibleForUser((int)$user['id']); $wallets=Wallet::visibleForUser((int)$user['id']); $primaryUrl=null; if(!empty($user['primary_link_id'])){ foreach($links as $l){ if((int)$l['id']===(int)$user['primary_link_id']){$primaryUrl=$l['url']; break;} } } if(!$primaryUrl){ foreach($wallets as $w){ if(!empty($w['payment_uri'])){ $primaryUrl=$w['payment_uri']; break; } } } ob_start(); require APP_BASE_PATH.'/app/Views/page/public.php'; $content=ob_get_clean(); $display=$user['display_name']?:$user['username']; $pageTitle='Support by Donate to '.$display.' with PiggyTip.me'; $pageDescription='Support '.$display.' with one link via PiggyTip.me — PayPal, Binance Pay, and crypto wallets.'; ob_start(); require APP_BASE_PATH.'/app/Views/layouts/main.php'; return ob_get_clean(); }
  public static function notFound(): string { http_response_code(404); $pageTitle='Not found'; ob_start(); ?>
  <div class="min-h-[60vh] flex items-center justify-center"><div class="text-center"><h1 class="text-3xl font-semibold mb-2">Page not found</h1><a class="text-blue-600" href="<?= Helpers::e(Helpers::url('/')) ?>">Back to home</a></div></div>
  <?php $content=ob_get_clean(); ob_start(); require APP_BASE_PATH.'/app/Views/layouts/main.php'; return ob_get_clean(); }

  public static function becomeSponsor(): string {
    $pageTitle = 'Become a Sponsor'; ob_start(); ?>
    <section class="container mx-auto px-4 py-14 max-w-2xl text-center">
      <h1 class="text-3xl font-semibold mb-4">Become a Sponsor</h1>
      <div class="ui-card rounded-2xl p-8">
        <p class="text-lg">For sponsorship inquiries, please contact</p>
        <p class="mt-2 text-2xl font-semibold"><a class="text-primary-500 hover:underline" href="mailto:barangsanji@gmail.com">barangsanji@gmail.com</a></p>
      </div>
    </section>
    <?php $content=ob_get_clean(); ob_start(); require APP_BASE_PATH.'/app/Views/layouts/main.php'; return ob_get_clean();
  }
}
