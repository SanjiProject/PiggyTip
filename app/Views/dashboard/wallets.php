<?php use App\Lib\Helpers; ?>
<div class="grid lg:grid-cols-2 gap-6">
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
    <h3 class="text-lg font-semibold mb-4">Add wallet</h3>
    <form method="post" action="<?= Helpers::e(Helpers::url('/wallets/create')) ?>" class="grid gap-4">
      <?= Helpers::csrfField() ?>
      <?php 
        $coins = [
          ['key'=>'USDT','label'=>'USDT (TRC-20)','chain'=>'TRON','placeholder'=>'tron:...'],
          ['key'=>'USDT','label'=>'USDT (ERC-20)','chain'=>'ETH','placeholder'=>'ethereum:...'],
          ['key'=>'USDT','label'=>'USDT (BEP-20)','chain'=>'BSC','placeholder'=>'bsc:...'],
          ['key'=>'BTC','label'=>'BTC','chain'=>'BITCOIN','placeholder'=>'bitcoin:...'],
          ['key'=>'ETH','label'=>'ETH','chain'=>'ETH','placeholder'=>'ethereum:...'],
          ['key'=>'BNB','label'=>'BNB','chain'=>'BSC','placeholder'=>'bsc:...'],
          ['key'=>'SOL','label'=>'SOL','chain'=>'SOL','placeholder'=>'solana:...'],
          ['key'=>'TRX','label'=>'TRX','chain'=>'TRON','placeholder'=>'tron:...'],
        ];
      ?>
      <div>
        <label class="block text-sm mb-1">Coin</label>
        <div class="relative">
          <button type="button" id="coinMenuBtn" class="w-full flex items-center justify-between px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
            <span class="flex items-center gap-2">
              <?= App\Lib\Helpers::brandIconImg('usdt') ?>
              <span id="coinMenuLabel">USDT (TRC-20)</span>
            </span>
            <svg class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div id="coinMenu" class="absolute mt-1 w-full hidden z-10 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-soft">
            <ul class="max-h-72 overflow-auto py-1">
              <?php foreach ($coins as $c): ?>
                <li>
                  <button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 flex items-center gap-2" data-coin='<?= json_encode($c, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>'>
                    <?= App\Lib\Helpers::brandIconImg($c['key']) ?>
                    <span><?= Helpers::e($c['label']) ?></span>
                  </button>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
      <input type="hidden" name="label" value="USDT (TRC-20)" />
      <!-- external icon slot removed; icon lives in dropdown button -->
      <div class="grid sm:grid-cols-2 gap-3">
        <div>
          <label class="block text-sm mb-1">Chain</label>
          <input name="chain" value="TRON" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" />
        </div>
        <div>
          <label class="block text-sm mb-1">Payment URI (optional)</label>
          <input name="payment_uri" placeholder="tron:TC..." class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" />
        </div>
      </div>
      <div>
        <label class="block text-sm mb-1">Address</label>
        <input name="address" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" required />
      </div>
      <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_visible" checked class="scale-110"> Visible</label>
      <button class="justify-self-start px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Add</button>
    </form>
  </div>
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
    <h3 class="text-lg font-semibold mb-4">Your wallets</h3>
    <ul class="space-y-3">
      <?php foreach ($wallets as $w): ?>
        <li class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
              <form method="post" action="<?= Helpers::e(Helpers::url('/wallets/update/' . (int)$w['id'])) ?>" class="grid gap-3 md:grid-cols-2 items-start">
                <?= Helpers::csrfField() ?>
                <div class="flex items-center gap-2">
                  <?php $det = App\Lib\Helpers::coinDetect($w['label'] ?? null, $w['chain'] ?? null); $sym = $det['symbol'] ?? null; if($sym){ echo App\Lib\Helpers::brandIconImg($sym); } ?>
                  <input name="label" value="<?= Helpers::e($w['label']) ?>" class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-900 flex-1" />
                </div>
                <input name="chain" value="<?= Helpers::e($w['chain']) ?>" class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
                <input name="address" value="<?= Helpers::e($w['address']) ?>" class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-900 md:col-span-2" />
                <input name="payment_uri" value="<?= Helpers::e($w['payment_uri']) ?>" placeholder="tron:..." class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-900 md:col-span-2" />
                <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_visible" <?= $w['is_visible']? 'checked':'' ?> class="scale-110"> Visible</label>
                <div class="flex items-center gap-2 md:justify-end">
                  <button class="px-3 py-2 rounded-lg border">Save</button>
                  <button formaction="<?= Helpers::e(Helpers::url('/wallets/delete/' . (int)$w['id'])) ?>" formmethod="post" name="delete" value="1" class="px-3 py-2 rounded-lg border" onclick="return confirm('Delete wallet?')">
                    <?= 'Delete' ?>
                  </button>
                </div>
              </form>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<script>
  // Coin dropdown behavior for Add wallet
  (function(){
    const btn = document.getElementById('coinMenuBtn');
    const menu = document.getElementById('coinMenu');
    const label = document.querySelector('input[name="label"]');
    const chain = document.querySelector('input[name="chain"]');
    const uri = document.querySelector('input[name="payment_uri"]');
    const slot = document.querySelector('#coinMenuBtn > span.flex.items-center.gap-2');
    const btnText = document.getElementById('coinMenuLabel');
    function setIcon(key){
      if(!slot) return;
      const k = (key||'').toLowerCase();
      const map = { usdt:{slug:'tether',color:'26A17B'}, btc:{slug:'bitcoin',color:'F7931A'}, eth:{slug:'ethereum',color:'627EEA'}, bnb:{slug:'binance',color:'F3BA2F'}, sol:{slug:'solana',color:'00FFA3'}, trx:{slug:'tron',color:'EF002A'} };
      const it = map[k] || null;
      if(!it){ return; }
      const src = it.slug==='tron' ? `<?= Helpers::e(Helpers::url('/index.php?route=/icon/tron.svg')) ?>` : `https://cdn.simpleicons.org/${it.slug}/${it.color}`;
      const fallback = it.slug==='tron' ? `<?= Helpers::e(Helpers::url('/assets/fallbacks/tron.svg')) ?>` : `https://api.iconify.design/simple-icons:${it.slug}.svg?color=%23${it.color}`;
      const existingImg = slot.querySelector('img');
      const imgHtml = `<img src="${src}" onerror="this.onerror=null;this.src='${fallback}'" alt="${k.toUpperCase()}" class="h-5 w-5" loading="lazy" width="20" height="20" />`;
      if (existingImg) { existingImg.outerHTML = imgHtml; }
      else slot.insertAdjacentHTML('afterbegin', imgHtml);
    }
    function close(){ menu?.classList.add('hidden'); document.removeEventListener('click', onDoc, true); }
    function onDoc(e){ if(!menu.contains(e.target) && !btn.contains(e.target)) close(); }
    btn?.addEventListener('click', ()=>{ menu?.classList.toggle('hidden'); if(!menu.classList.contains('hidden')) setTimeout(()=>document.addEventListener('click', onDoc, true),0); });
    menu?.querySelectorAll('[data-coin]')?.forEach(el=>{
      el.addEventListener('click', ()=>{
        try{
          const data = JSON.parse(el.getAttribute('data-coin'));
          if(label) label.value = data.label || '';
          if(chain) chain.value = data.chain || '';
          if(uri){ uri.placeholder = data.placeholder || ''; uri.value = '' }
          setIcon(data.key);
          if(btnText) btnText.textContent = data.label || '';
        }catch(err){}
        close();
      });
    });
  })();
</script>
<!-- removed old live label preview; dropdown controls icon now -->
