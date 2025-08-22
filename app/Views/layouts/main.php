<?php use App\Lib\Helpers; ?>
<?php $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/'; $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http'; $host = $_SERVER['HTTP_HOST'] ?? 'localhost'; $reqUri = $_SERVER['REQUEST_URI'] ?? '/'; $canonicalUrl = $scheme . '://' . $host . $reqUri; ?>
<!doctype html>
<html lang="en" class="h-full dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= Helpers::e($pageTitle ?? 'PiggyTip.me') ?></title>
  <meta name="description" content="<?= Helpers::e($pageDescription ?? 'PiggyTip.me helps creators collect support with one beautiful link. Add PayPal, Binance Pay, and crypto wallets. Simple, fast, privacy-friendly analytics.') ?>" />
  <link rel="canonical" href="<?= Helpers::e($canonicalUrl) ?>" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="<?= Helpers::e(Helpers::url('/favicon.ico')) ?>" />
  <link rel="shortcut icon" type="image/x-icon" href="<?= Helpers::e(Helpers::url('/favicon.ico')) ?>" />
  <link rel="apple-touch-icon" href="<?= Helpers::e(Helpers::url('/img/logo.webp')) ?>" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT:'#FF96AF',
              50:'#FFF1F4', 100:'#FFE4EA', 200:'#FFC8D3', 300:'#FFA9BE', 400:'#FF8AAA',
              500:'#FF6C97', 600:'#FF5289', 700:'#E24B78', 800:'#BF3A62', 900:'#8F2B49'
            }
          },
          boxShadow:{ soft:'0 10px 30px -10px rgba(2,6,23,.25)'}
        }
      }
    }
  </script>
  <meta name="color-scheme" content="dark">
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="PiggyTip.me" />
  <meta property="og:title" content="<?= Helpers::e($pageTitle ?? 'PiggyTip.me') ?>" />
  <meta property="og:description" content="<?= Helpers::e($pageDescription ?? 'PiggyTip.me helps creators collect support with one beautiful link. Add PayPal, Binance Pay, and crypto wallets.') ?>" />
  <meta property="og:url" content="<?= Helpers::e($canonicalUrl) ?>" />
  <meta property="og:image" content="<?= Helpers::e(Helpers::url('/img/logo.webp')) ?>" />
  <meta name="twitter:card" content="summary_large_image" />
  <style>
    /* Dark design tokens */
    :root{
      --bg:#0b1220;            /* page background */
      --bg2:#0e1629;           /* subdued surfaces */
      --surface:#0f172a;       /* cards */
      --surface2:#0b1220;      /* deeper sections */
      --border:rgba(148,163,184,.14);
      --border-strong:rgba(148,163,184,.24);
      --text:#e5e7eb;
      --muted:#cbd5e1; /* higher contrast muted text for dark bg */
      --accent:#FF96AF;
      --ring:#FF96AF;
      --shadow:0 20px 40px -24px rgba(2,6,23,.7);
    }

    .container{max-width:1120px}
    @media (min-width:1280px){ .container{ max-width:1200px } }
    html,body{font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, "Apple Color Emoji", "Segoe UI Emoji";}
    html,body{color:var(--text)}
    /* Remove default browser margins to avoid top gap */
    html,body{margin:0; padding:0}

    /* Subtle grid and backdrop */
    .bg-grid {background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.04) 1px, transparent 0); background-size: 24px 24px}
    .dark .bg-grid {background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.06) 1px, transparent 0)}

    /* Cards and items */
    .ui-card{
      background:linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,0)) , var(--surface);
      border:1px solid var(--border);
      border-radius:16px; box-shadow:var(--shadow);
    }
    .sponsor-card{
      position: relative;
      border:1px solid var(--border);
      border-radius:12px;
      background: linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,0)),
                  radial-gradient(400px 200px at 50% 0%, rgba(255,150,175,.12), transparent 60%);
      transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
      box-shadow: 0 8px 24px -18px rgba(255,150,175,.35);
    }
    .sponsor-card:hover{
      transform: translateY(-2px);
      border-color: var(--border-strong);
      box-shadow: 0 16px 32px -18px rgba(255,150,175,.55);
    }
    .ui-item{
      background:linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,0)) , var(--bg2);
      border:1px solid var(--border); border-radius:14px;
      transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .ui-item:hover{ border-color:var(--border-strong); box-shadow:0 12px 28px -24px rgba(0,0,0,.8); transform: translateY(-1px); }

    /* Inputs */
    .dark input,.dark textarea,.dark select{
      background:var(--bg2); color:var(--text); border:1px solid var(--border);
      border-radius:12px;
      transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease, color .2s ease;
    }
    .dark input::placeholder,.dark textarea::placeholder{color:var(--muted)}
    .dark input:focus,.dark textarea:focus,.dark select:focus{outline:none; box-shadow:0 0 0 3px rgba(255,150,175,.35); border-color:var(--ring)}
    .dark label{color:var(--text)}

    /* Primary accents (turn Tailwind primary backgrounds into soft gradients in dark) */
    .dark .bg-primary-600{ background-image: linear-gradient(135deg, #FF96AF, #FF6C97); }
    .dark .hover\:bg-primary-700:hover{ filter: brightness(1.05); }

    /* Borders and subtle surfaces from Tailwind utilities */
    .dark .border{ border-color: var(--border) !important; }
    .dark .border-gray-200{ border-color: var(--border) !important; }
    .dark .border-gray-300{ border-color: var(--border) !important; }
    .dark .border-gray-700{ border-color: var(--border-strong) !important; }
    .dark .bg-white{ background-color: var(--surface) !important; }
    .dark .bg-gray-900{ background-color: var(--surface2) !important; }
    .dark .bg-gray-800{ background-color: var(--surface) !important; }
    .dark .text-gray-400{ color: var(--muted) !important; }
    .dark .text-gray-500{ color: var(--muted) !important; }
    .dark .text-gray-600{ color: #e2e8f0 !important; }
    .dark .text-gray-700{ color: #e5e7eb !important; }

    /* Header */
    .site-header{ backdrop-filter: blur(10px); background: linear-gradient(180deg, rgba(10,10,10,.92), rgba(15,23,42,.7)); border-bottom:1px solid var(--border); }
    .site-header .brand-dot{ background-image: linear-gradient(135deg,#FF96AF,#FF6C97) !important; box-shadow: 0 10px 30px -12px rgba(255,150,175,.45); }
    .site-header nav a.nav-link{ color: var(--muted); padding:.5rem .6rem; border-radius:.75rem; transition: color .2s ease, background-color .2s ease, border-color .2s ease, transform .2s ease; }
    .site-header nav a.nav-link:hover{ color:#e5e7eb; background: rgba(148,163,184,.08); }
    .site-header nav a.nav-link.nav-active, .site-header nav a.nav-link[aria-current="page"]{ color:#e5e7eb; background: rgba(148,163,184,.12); border:1px solid var(--border); }
    .site-header nav a.nav-cta{ color:#fff; background-image: linear-gradient(135deg,#FF96AF,#FF6C97); box-shadow: 0 12px 24px -16px rgba(255,150,175,.55); padding:.5rem .9rem; }
    .site-header nav a.nav-cta:hover{ filter: brightness(1.06); }

    /* Links in content (avoid overriding button-like anchors) */
    .dark main a:not(.nav-link):not(.nav-cta):not([class*="bg-"]):not([class*="btn"]):not([class*="px-"]){ color: #FF96AF; }
    .dark main a:not(.nav-link):not(.nav-cta):not([class*="bg-"]):not([class*="btn"]):not([class*="px-"]):hover{ color: #ffb3c3; }
    /* Ensure pink buttons keep readable text */
    .dark .bg-primary-600, .dark .bg-primary-700{ color: #ffffff !important; }
    /* Fix light hover backgrounds in dark */
    .dark .hover\:bg-gray-50:hover{ background-color: rgba(255,150,175,.10) !important; }
    .dark a.border:hover, .dark button.border:hover{ background-color: rgba(255,150,175,.10) !important; border-color: rgba(255,150,175,.35) !important; color:#fff !important; }

    /* Improve readability for accent panels (e.g., Get started box) */
    .dark .bg-primary-50{ background-color: rgba(255,150,175,.12) !important; border-color: rgba(255,150,175,.28) !important; }
    .dark .bg-primary-900\/20{ background-color: rgba(255,150,175,.12) !important; border-color: rgba(255,150,175,.28) !important; }

    /* Toast refinement */
    #toast{ backdrop-filter: blur(8px); border:1px solid var(--border); box-shadow: var(--shadow); }

    /* Scrollbars */
    *{ scrollbar-width: thin; scrollbar-color: rgba(148,163,184,.35) rgba(17,24,39,.6); }
    *::-webkit-scrollbar{ height:10px; width:10px }
    *::-webkit-scrollbar-track{ background: rgba(17,24,39,.6); border-radius:10px }
    *::-webkit-scrollbar-thumb{ background: linear-gradient(180deg, rgba(148,163,184,.45), rgba(148,163,184,.25)); border-radius:10px; border:2px solid rgba(17,24,39,.6) }

    /* Better focus visibility */
    :focus-visible{ outline: none; box-shadow: 0 0 0 3px rgba(255,150,175,.45); border-color: var(--ring) !important; }

    /* Mobile menu icon uses image */
    #menuBtn{ position: relative; color: var(--muted); display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; border:none; background:transparent }
    #menuBtn img{ height:36px; width:36px; display:block }
    #menuBtn:hover{ filter: brightness(1.1) }
    @media (min-width: 768px){ #menuBtn{ display:none !important } }

    /* Animated background: rich dark with moving accent glows */
    .site-bg{
      background-color:#0b0f1a;
      position: relative;
      overflow-x: hidden;
    }
    .site-bg::before{
      content: "";
      position: fixed; inset: 0; z-index: -1; pointer-events: none;
      background-image:
        radial-gradient( 1100px 700px at 16% 22%, rgba(255,150,175,.38), transparent 65%),
        radial-gradient( 1000px 650px at 84% 78%, rgba(255,108,151,.26), transparent 66%),
        linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,0)),
        linear-gradient(120deg, #0a0d18, #0f1a2e 50%, #0a0d18 100%);
      background-repeat: no-repeat;
      background-size: 280% 280%, 280% 280%, 120% 120%, 260% 260%;
      background-position: 0% 0%, 100% 100%, 50% 50%, 0% 50%;
      will-change: background-position, transform;
      animation: bg-pan 28s ease-in-out infinite;
    }

    /* Utility: pink button with black text (overrides global white on pink) */
    .btn-pink-black{ color:#0b0b0b !important; font-weight:600; }
    .btn-pink-black:hover{ color:#0b0b0b !important; }
    /* Utility: force true white background (bypass dark mode bg-white override) */
    .icon-white{ background-color:#ffffff !important; }
    @keyframes bg-pan{
      0%   { transform: translate3d(0,0,0); background-position: 0% 0%,   100% 100%, 50% 50%, 0% 50%; }
      40%  { transform: translate3d(0,0,0); background-position: 80% 20%,  20%   80%, 50% 50%, 80% 60%; }
      70%  { transform: translate3d(0,0,0); background-position: 30% 80%,  70%   30%, 50% 50%, 30% 40%; }
      100% { transform: translate3d(0,0,0); background-position: 0% 0%,   100% 100%, 50% 50%, 0% 50%; }
    }
  </style>
</head>
<body class="min-h-screen site-bg text-gray-100">
  <header class="site-header fixed top-0 left-0 right-0 z-40">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <a href="<?= Helpers::e(Helpers::url('/')) ?>" class="group flex items-center gap-3 font-semibold">
        <img src="<?= Helpers::e(Helpers::url('/img/logo.webp')) ?>" alt="Logo" class="h-9 w-9 md:h-10 md:w-10 object-contain shadow-soft group-hover:scale-105 transition" />
        <span>PiggyTip.me</span>
      </a>
      <nav class="hidden md:flex items-center gap-5 text-sm">
        <?php if (!empty($_SESSION['user_id'])): ?>
          <a class="nav-link <?= $currentPath === '/dashboard' ? 'nav-active' : '' ?>" href="<?= Helpers::e(Helpers::url('/dashboard')) ?>">Dashboard</a>
          <a class="nav-link <?= $currentPath === '/dashboard/payments' ? 'nav-active' : '' ?>" href="<?= Helpers::e(Helpers::url('/dashboard/payments')) ?>">Payments</a>
          <a class="nav-link <?= $currentPath === '/dashboard/profile' ? 'nav-active' : '' ?>" href="<?= Helpers::e(Helpers::url('/dashboard/profile')) ?>">Profile</a>
          <a class="nav-link" href="<?= Helpers::e(Helpers::url('/logout')) ?>">Logout</a>
        <?php else: ?>
          <a class="nav-link <?= $currentPath === '/login' ? 'nav-active' : '' ?>" href="<?= Helpers::e(Helpers::url('/login')) ?>">Login</a>
          <a class="nav-cta rounded-lg" href="<?= Helpers::e(Helpers::url('/register')) ?>">Get started</a>
        <?php endif; ?>
        
      </nav>
      <button id="menuBtn" class="md:hidden p-1 rounded" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobileNav">
        <img src="<?= Helpers::e(Helpers::url('/img/toggle.webp')) ?>" alt="Toggle" />
      </button>
    </div>
    <div id="mobileNav" class="md:hidden hidden border-t border-gray-200 dark:border-gray-800">
      <div class="container mx-auto px-4 py-2 flex flex-wrap gap-3 text-sm">
        <?php if (!empty($_SESSION['user_id'])): ?>
          <a class="px-3 py-1 rounded border" href="<?= Helpers::e(Helpers::url('/dashboard')) ?>">Dashboard</a>
          <a class="px-3 py-1 rounded border" href="<?= Helpers::e(Helpers::url('/dashboard/payments')) ?>">Payments</a>
          <a class="px-3 py-1 rounded border" href="<?= Helpers::e(Helpers::url('/dashboard/profile')) ?>">Profile</a>
          <a class="px-3 py-1 rounded border" href="<?= Helpers::e(Helpers::url('/logout')) ?>">Logout</a>
        <?php else: ?>
          <a class="px-3 py-1 rounded border" href="<?= Helpers::e(Helpers::url('/login')) ?>">Login</a>
          <a class="px-3 py-1 rounded border bg-primary-600 text-white" href="<?= Helpers::e(Helpers::url('/register')) ?>">Get started</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="container mx-auto px-4 mt-4"><div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-200 p-3 rounded shadow-soft"><?= Helpers::e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="container mx-auto px-4 mt-4"><div class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-200 p-3 rounded shadow-soft"><?= Helpers::e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?></div></div>
  <?php endif; ?>

  <main id="pageMain" class="container mx-auto px-4 py-8">
    <?= $content ?? '' ?>
  </main>

  <footer class="py-10 text-sm text-gray-400 border-t border-gray-200 dark:border-gray-800">
    <div class="container mx-auto px-4 grid gap-4 sm:grid-cols-2 items-center">
      <div class="text-center sm:text-left">
        <span class="text-gray-500">© <?= date('Y') ?> PiggyTip.me</span>
      </div>
      <div class="text-center sm:text-right">
        <span class="text-gray-500">Made with <span aria-hidden="true">❤️</span> by SanjiProject</span>
      </div>
    </div>
  </footer>

  <div id="toast" class="fixed left-1/2 -translate-x-1/2 bottom-6 hidden px-4 py-2 rounded-lg bg-gray-900 text-white text-sm shadow-lg" role="status" aria-live="polite"></div>

  <script>
    // Minimal toast helper (replaces external assets/js/app.js)
    window.showToast = function(msg){
      const el = document.getElementById('toast'); if(!el) return;
      el.textContent = msg; el.classList.remove('hidden'); el.style.opacity = '1';
      setTimeout(()=>{ el.style.transition='opacity .4s'; el.style.opacity='0'; setTimeout(()=>{ el.classList.add('hidden'); el.style.transition=''; }, 400); }, 1200);
    }

    document.addEventListener('DOMContentLoaded', () => {
      // Offset body for fixed header height (dynamic)
      const headerEl = document.querySelector('header.site-header');
      const applyHeaderOffset = () => {
        if (!headerEl) return;
        document.body.style.paddingTop = headerEl.offsetHeight + 'px';
      };
      applyHeaderOffset();
      window.addEventListener('resize', applyHeaderOffset);

      const menuBtn = document.getElementById('menuBtn');
      const mobileNav = document.getElementById('mobileNav');
      if (menuBtn && mobileNav) {
        menuBtn.addEventListener('click', () => {
          const hidden = mobileNav.classList.toggle('hidden');
          menuBtn.setAttribute('aria-expanded', hidden ? 'false' : 'true');
        });
      }

      // Ensure CSRF token is present on all POST forms
      const csrf = '<?= App\Lib\Helpers::e(App\Lib\Helpers::csrfToken()) ?>';
      document.querySelectorAll('form[method="post"]').forEach(f=>{
        if(!f.querySelector('input[name="_token"]')){
          const i=document.createElement('input'); i.type='hidden'; i.name='_token'; i.value=csrf; f.appendChild(i);
        }
      });
    });
  </script>
</body>
</html>
