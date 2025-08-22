<?php use App\Lib\Helpers; ?>
<section class="max-w-xl mx-auto">
  <div class="text-center mb-4">
    <img src="<?= Helpers::e(Helpers::url('/img/piggy.webp')) ?>" alt="Piggy" class="mx-auto h-16 w-16 md:h-20 md:w-20 object-contain mb-2" />
    <h1 class="text-xl md:text-2xl font-semibold">Donate to <?= Helpers::e($user['display_name'] ?: $user['username']) ?></h1>
    <p class="mt-1 text-sm text-gray-400">Love and Support <?= Helpers::e($user['display_name'] ?: $user['username']) ?> with Donating Through PiggyTip.me</p>
  </div>
  <div class="ui-card rounded-[28px] p-6">
    <div class="rounded-2xl border p-5 text-center relative">
      <img src="<?= Helpers::e(Helpers::url('/img/swag.webp')) ?>" alt="Swag" class="absolute -left-3 top-1/2 -translate-y-1/2 h-8 w-8 md:h-10 md:w-10 object-contain" />
      <img src="<?= Helpers::e($user['avatar'] ? (str_starts_with($user['avatar'],'http') ? $user['avatar'] : Helpers::url(ltrim($user['avatar'],'/'))) : ('https://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '?s=200&d=mp')) ?>" alt="Avatar" class="w-16 h-16 md:w-20 md:h-20 rounded-full object-cover mx-auto border shadow mb-3">
      <div class="font-medium text-white"><?= Helpers::e($user['display_name'] ?: $user['username']) ?></div>
      <?php if (!empty($user['bio'])): ?>
        <p class="mt-1 text-white/80 text-sm"><?= Helpers::e($user['bio']) ?></p>
      <?php endif; ?>
      <?php /* Donate button intentionally removed: all actions shown uniformly below */ ?>

      <?php
        // Build unified list from links and wallets to match dashboard preview
        $listItems = [];
        foreach (($links ?? []) as $l) {
          $listItems[] = ['label'=>$l['label'], 'href'=>$l['url'], 'type'=>'link', 'id'=>$l['id']];
        }
        foreach (($wallets ?? []) as $w) {
          $label = $w['label'];
          $href = $w['payment_uri'] ?: ($w['address'] ?: '#');
          $symbol = App\Lib\Helpers::coinDetect($w['label'] ?? null, $w['chain'] ?? null)['symbol'] ?? null;
          $listItems[] = ['label'=>$label, 'href'=>$href, 'type'=>'wallet', 'symbol'=>$symbol];
        }
      ?>
      <?php if (!empty($listItems)): ?>
        <div class="mt-4 space-y-3">
          <?php foreach ($listItems as $it): ?>
            <?php if ($it['type']==='link'): ?>
              <?php 
                $t = strtolower((string)($it['type'] ?? 'link'));
                $labelLower = strtolower((string)($it['label'] ?? ''));
                $iconKey = 'link';
                if (str_contains($labelLower, 'paypal')) $iconKey = 'paypal';
                elseif (str_contains($labelLower, 'binance')) $iconKey = 'bnb';
                elseif (str_contains($labelLower, 'usdt')) $iconKey = 'usdt';
                elseif (str_contains($labelLower, 'cash app') || str_contains($labelLower, 'cashapp')) $iconKey = 'cashapp';
                elseif (str_contains($labelLower, 'patreon')) $iconKey = 'patreon';
                elseif (str_contains($labelLower, 'coffee')) $iconKey = 'buymeacoffee';
                elseif (str_contains($labelLower, 'ko-fi') || str_contains($labelLower, 'kofi')) $iconKey = 'kofi';
                elseif (str_contains($labelLower, 'stripe')) $iconKey = 'stripe';
              ?>
              <a href="<?= Helpers::e(Helpers::url('/r/' . (int)$it['id'])) ?>" target="_blank" rel="noopener noreferrer" class="block text-center py-3 rounded-2xl bg-primary-600 hover:bg-primary-700 btn-pink-black flex items-center gap-3 justify-center">
                <?= App\Lib\Helpers::brandIconImg($iconKey,'h-5 w-5 rounded-full icon-white p-1') ?>
                <span><?= Helpers::e($it['label']) ?></span>
              </a>
            <?php else: ?>
              <?php $symKey = strtolower((string)($it['symbol'] ?? '')); if ($symKey === '') { $symKey = 'link'; } ?>
              <button type="button" class="w-full py-3 rounded-2xl bg-primary-600 hover:bg-primary-700 btn-pink-black flex items-center gap-3 justify-center" data-open-crypto <?= !empty($it['symbol'])? 'data-symbol="'.Helpers::e($it['symbol']).'"':'' ?>>
                <?= App\Lib\Helpers::brandIconImg($symKey,'h-5 w-5 rounded-full icon-white p-1') ?>
                <span><?= Helpers::e($it['label']) ?></span>
              </button>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <img src="<?= Helpers::e(Helpers::url('/img/swag.webp')) ?>" alt="Swag" class="absolute -bottom-3 right-3 h-8 w-8 md:h-10 md:w-10 object-contain" />
    </div>
  </div>
  <p class="text-xs text-gray-500 mt-6 text-center"></p>
</section>
<section class="max-w-xl mx-auto mt-6">
  <div class="ui-card rounded-2xl p-5">
    <h3 class="text-lg font-semibold mb-3">Leave a message</h3>
    <form id="commentForm" class="grid gap-3">
      <div>
        <label class="block text-sm mb-1">Your name</label>
        <input type="text" name="name" id="cName" maxlength="60" required class="w-full px-3 py-2 rounded-xl border bg-white dark:bg-gray-900 text-sm md:text-base" placeholder="CoolPiggyName" />
      </div>
      <div>
        <label class="block text-sm mb-1">Comment</label>
        <textarea name="comment" id="cBody" maxlength="500" required class="w-full px-3 py-2 rounded-xl border bg-white dark:bg-gray-900 text-sm md:text-base" placeholder="Love From Piggy"></textarea>
      </div>
      <div>
        <label class="block text-sm mb-1">Captcha</label>
        <div class="flex gap-2 items-center">
          <input type="text" id="cQuestion" class="flex-1 min-w-0 px-3 py-2 rounded-xl border bg-white dark:bg-gray-900 text-sm md:text-base" value="" placeholder="Loading..." disabled />
          <input type="number" id="cAnswer" class="w-24 sm:w-28 px-3 py-2 rounded-xl border bg-white dark:bg-gray-900 text-sm md:text-base" placeholder="Answer" required />
          <button type="button" id="cRefresh" class="px-2 py-2 rounded-xl border text-sm">↻</button>
        </div>
        <p class="text-xs text-gray-500 mt-1">Solve this Noob Equation</p>
      </div>
      <button class="px-4 py-2 md:py-3 rounded-xl bg-primary-600 hover:bg-primary-700 btn-pink-black text-sm md:text-base" type="submit">Post</button>
      <p id="cMsg" class="text-sm text-gray-400"></p>
    </form>
  </div>
  <div class="ui-card rounded-2xl p-5 mt-4">
    <h3 class="text-lg font-semibold mb-3">Love Message Received for <?= Helpers::e($user['display_name'] ?: $user['username']) ?></h3>
    <div id="commentsList" class="space-y-3"></div>
  </div>
</section>
<?php 
  // Build crypto options from available wallets
  $cryptoOptions = [];
  foreach ($wallets ?? [] as $w) {
    $labelUpper = strtoupper((string)$w['label']);
    $symbol = null; $network = null;
    foreach (['USDT','BTC','ETH','BNB','SOL','TRX'] as $sym) { if (str_contains($labelUpper,$sym)) { $symbol=$sym; break; } }
    if (!$symbol && isset($w['chain'])) {
      $chainUpper = strtoupper((string)$w['chain']);
      $symbol = match(true){ str_contains($chainUpper,'TRON') => ($labelUpper && str_contains($labelUpper,'USDT')?'USDT':'TRX'), str_contains($chainUpper,'ETH') => 'ETH', str_contains($chainUpper,'BSC')||str_contains($chainUpper,'BNB') => 'BNB', str_contains($chainUpper,'SOL') => 'SOL', default => null };
    }
    if (!$symbol) continue;
    // Determine network subtype for USDT
    $chain = strtoupper((string)($w['chain'] ?? ''));
    if ($symbol==='USDT') {
      $network = (str_contains($labelUpper,'TRC')||str_contains($chain,'TRON')) ? 'TRC-20' : ((str_contains($labelUpper,'ERC')||str_contains($chain,'ETH')) ? 'ERC-20' : ((str_contains($labelUpper,'BEP')||str_contains($chain,'BSC')||str_contains($chain,'BNB')) ? 'BEP-20' : (str_contains($chain,'SOL')?'SOL':'USDT')));
    } else {
      $network = $w['chain'] ?: $symbol;
    }
    $cryptoOptions[] = [
      'symbol'=>$symbol,
      'label'=>$w['label'],
      'network'=>$network,
      'address'=>$w['address'],
      'payment_uri'=>$w['payment_uri'] ?: $w['address'],
    ];
  }
?>

<?php if (!empty($cryptoOptions)): ?>
<div id="cryptoModal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog" aria-labelledby="cryptoModalTitle">
  <div class="absolute inset-0 bg-black/50" data-close-crypto></div>
  <div class="relative mx-auto my-10 max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-soft border border-gray-200 dark:border-gray-800 p-5">
    <div class="flex items-center justify-between mb-3">
      <h3 id="cryptoModalTitle" class="text-lg font-semibold">Choose a crypto</h3>
      <button type="button" class="px-2 py-1 rounded border border-gray-300 dark:border-gray-700" data-close-crypto aria-label="Close">Close</button>
    </div>
    <div class="grid grid-cols-1 gap-3" id="cryptoOptions">
      <?php foreach ($cryptoOptions as $opt): ?>
        <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
          <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <?php $color = match($opt['symbol']){ 'BTC'=>'#F7931A','ETH'=>'#627EEA','USDT'=>'#26A17B','BNB'=>'#F3BA2F','SOL'=>'#14F195','TRX'=>'#EB0029', default=>'#6B7280'}; ?>
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white" style="background: <?= Helpers::e($color) ?>;">
                <span class="text-xs font-bold"><?= Helpers::e($opt['symbol']) ?></span>
              </span>
              <div>
                <div class="font-medium">
                  <?= Helpers::e($opt['symbol']) ?>
                  <?php if (!empty($opt['network'])): ?><span class="text-xs text-gray-500">(<?= Helpers::e($opt['network']) ?>)</span><?php endif; ?>
                </div>
                <div class="text-xs text-gray-500 select-all">
                  <?= Helpers::e(Helpers::middleTruncate($opt['address'], 28)) ?>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button class="px-3 py-1.5 rounded-lg border" data-copy="<?= Helpers::e($opt['address']) ?>">Copy</button>
              <button class="px-3 py-1.5 rounded-lg border" data-qr="<?= Helpers::e($opt['payment_uri']) ?>">QR</button>
            </div>
          </div>
          <canvas class="mt-3 hidden" data-qr-canvas width="256" height="256" aria-hidden="true"></canvas>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  
</div>
<?php endif; ?>
<script>
  document.querySelectorAll('[data-copy]')?.forEach(btn=>{ btn.addEventListener('click', async()=>{ try{ await navigator.clipboard.writeText(btn.dataset.copy||''); showToast('Address copied'); }catch(e){} }); });
  document.querySelectorAll('[data-qr]')?.forEach(btn=>{ btn.addEventListener('click', ()=>{ const card=btn.closest('.p-4'); const canvas=card.querySelector('[data-qr-canvas]'); const link=btn.dataset.qr||''; canvas.classList.remove('hidden'); window.QRCode.toCanvas(canvas, link, { width: 256 }, ()=>{}); const dl=card.querySelector('[data-qr-download]'); dl.addEventListener('click',()=>{ dl.href=canvas.toDataURL('image/png'); },{once:true}); }); });
  // Crypto modal open/close and focus handling
  const modal = document.getElementById('cryptoModal');
  function openCrypto(targetSymbol){ if(!modal) return; modal.classList.remove('hidden'); if(targetSymbol){ const el=[...modal.querySelectorAll('[data-copy]')].find(e=>e.closest('.p-4')?.textContent?.includes(targetSymbol)); if(el){ el.focus(); } } }
  function closeCrypto(){ modal?.classList.add('hidden'); }
  document.querySelectorAll('[data-open-crypto]')?.forEach(btn=>{ btn.addEventListener('click',()=>{ track('click'); openCrypto(btn.dataset.symbol); }); });
  modal?.querySelectorAll('[data-close-crypto]')?.forEach(btn=> btn.addEventListener('click', closeCrypto));
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeCrypto(); });
  const PUBLIC_USER_ID=<?= (int)$user['id'] ?>; function track(event,linkId){ fetch('<?= Helpers::e(Helpers::url('/api/track')) ?>',{ method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({event, link_id: linkId||undefined, public_user_id: PUBLIC_USER_ID}) }); }
  track('pageview');
  document.querySelectorAll('[data-track-click]')?.forEach(el=>{ el.addEventListener('click',()=> track('click', el.dataset.linkId? Number(el.dataset.linkId):undefined)); });
  // Comments
  const submitBtn = document.querySelector('#commentForm button[type="submit"]');
  document.getElementById('commentForm')?.addEventListener('submit', async (e)=>{
    e.preventDefault(); const n=document.getElementById('cName'); const b=document.getElementById('cBody'); const m=document.getElementById('cMsg');
    const name=(n?.value||'').trim(); const body=(b?.value||'').trim(); if(!name||!body){ m.textContent='Please fill your name and comment.'; return; }
    // crude link check client-side
    if(/https?:\/\/|www\.|@[a-zA-Z0-9_.-]+\.[a-z]{2,}/i.test(body)){ m.textContent='Links are not allowed.'; return; }
    const captcha=(document.getElementById('cAnswer')?.value||'').trim(); if(!captcha){ m.textContent='Please solve the captcha.'; return; }
    m.textContent='Posting...';
    const res = await fetch('<?= Helpers::e(Helpers::url('/api/comment')) ?>',{ method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ public_user_id: PUBLIC_USER_ID, name, comment: body, captcha })});
    const json = await res.json().catch(()=>({ok:false}));
    if(json.ok){ m.textContent='Posted!'; b.value=''; document.getElementById('cAnswer').value=''; await loadCaptcha(); loadComments(); }
    else { m.textContent = json.error==='links_not_allowed' ? 'Links are not allowed.' : (json.error==='captcha'?'Captcha incorrect. Try again.':'Could not post, try again.'); }
  });
  async function loadCaptcha(){
    const q=document.getElementById('cQuestion');
    if(submitBtn) submitBtn.disabled = true;
    if(q) q.value='Loading...';
    try{
      const base='<?= Helpers::e(Helpers::url('/api/captcha')) ?>';
      const sep = base.includes('?') ? '&' : '?';
      const url = base + sep + 'uid=' + PUBLIC_USER_ID + '&_=' + Date.now();
      const res=await fetch(url, { headers: { 'Accept': 'application/json' } });
      const j=await res.json();
      if(j && j.q){ if(q) q.value=j.q; if(submitBtn) submitBtn.disabled=false; return; }
    }catch(e){}
    if(q) q.value='Tap ↻ to load captcha';
  }
  document.getElementById('cRefresh')?.addEventListener('click', loadCaptcha);
  loadCaptcha();
  async function loadComments(){
    try{
      const base='<?= Helpers::e(Helpers::url('/api/comments')) ?>';
      const sep = base.includes('?') ? '&' : '?';
      const url = base + sep + 'uid=' + PUBLIC_USER_ID + '&_=' + Date.now();
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      const list = document.getElementById('commentsList'); if(!list) return; list.innerHTML='';
      const items = await res.json().catch(()=>[]);
      (items||[]).forEach(c=>{
        const el=document.createElement('div'); el.className='p-3 rounded-xl border border-gray-200 dark:border-gray-800';
        const canDelete = <?= isset($_SESSION['user_id']) && (int)($_SESSION['user_id'])===(int)$user['id'] ? 'true':'false' ?>;
        const delBtn = canDelete ? `<button data-cid="${c.id}" class=\"text-xs text-red-400 hover:text-red-300 ml-2\">Delete</button>` : '';
        el.innerHTML = `<div class=\"text-sm text-gray-300 flex items-center gap-2\"><strong class=\"text-primary-500\">${c.name||'Anonymous'}</strong> <span class=\"text-gray-500 text-xs\">${(c.created_at||'').replace('T',' ')}</span> ${delBtn}</div><div class=\"mt-1\">${c.body}</div>`;
        if (canDelete) {
          el.querySelector('[data-cid]')?.addEventListener('click', async (ev)=>{
            const id = ev.currentTarget.getAttribute('data-cid');
            if(!id) return; ev.currentTarget.textContent='Deleting...';
            const form = new FormData(); form.append('id', id);
            const res = await fetch('<?= Helpers::e(Helpers::url('/api/comment/delete')) ?>', { method:'POST', body: form });
            const j = await res.json().catch(()=>({ok:false}));
            if(j.ok){ el.remove(); } else { ev.currentTarget.textContent='Delete'; }
          });
        }
        list.appendChild(el);
      });
    }catch(e){}
  }
  loadComments();
</script>
<?php
$display = $user['display_name'] ?: $user['username'];
$desc = $user['bio'] ?: 'Support me with a donation';
$canonical = App\Config::appUrl() . '/' . $user['username'];
$img = $user['avatar'] ?: ('https://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '?s=400&d=mp');
$donateTarget = $primaryUrl ?: ($links[0]['url'] ?? null);
?>
<meta property="og:title" content="<?= Helpers::e($display) ?>  Donate" />
<meta property="og:description" content="<?= Helpers::e($desc) ?>" />
<meta property="og:url" content="<?= Helpers::e($canonical) ?>" />
<meta property="og:image" content="<?= Helpers::e($img) ?>" />
<meta name="twitter:card" content="summary_large_image" />
<script type="application/ld+json">
<?= json_encode(['@context'=>'https://schema.org','@type'=>'Person','name'=>$display,'url'=>$canonical]) ?>
</script>
<?php if ($donateTarget): ?>
<script type="application/ld+json">
<?= json_encode(['@context'=>'https://schema.org','@type'=>'DonateAction','target'=>['type'=>'EntryPoint','urlTemplate'=>$donateTarget]]) ?>
</script>
<?php endif; ?>
