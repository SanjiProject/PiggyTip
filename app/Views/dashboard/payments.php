<?php use App\Lib\Helpers; ?>
<div class="max-w-3xl mx-auto">
  <div class="ui-card p-6">
    <h3 class="text-lg font-semibold mb-1">Add payments</h3>
    <p class="text-sm text-gray-500 mb-4">Click the button, choose your payment methods, paste details, and save. Up to 5 per submit.</p>
    <div class="flex items-center gap-3">
      <button type="button" id="openPaymentsModal" class="px-4 py-2 rounded-xl bg-primary-600 text-white hover:bg-primary-700">Add payment</button>
    </div>
    <div id="currentPayments" class="mt-6">
      <h4 class="text-sm font-medium mb-2">Your payments</h4>
      <div class="grid gap-3" id="currentPaymentsList">
        <?php if (!empty($wallets)): foreach ($wallets as $w): ?>
          <div class="ui-item p-3 flex items-center justify-between gap-3" data-wallet-row>
            <div class="min-w-0 flex items-center gap-2">
              <?php $det = App\Lib\Helpers::coinDetect($w['label'] ?? null, $w['chain'] ?? null); $sym = strtolower($det['symbol'] ?? 'link'); echo App\Lib\Helpers::brandIconImg($sym); ?>
              <div class="min-w-0">
                <div class="font-medium truncate" data-view><?= Helpers::e($w['label']) ?> <span class="text-xs text-gray-500"><?= Helpers::e($w['chain']) ?></span></div>
                <div class="text-xs text-gray-500 truncate" data-view><?= Helpers::e($w['address']) ?></div>
                <form class="inline-edit hidden mt-2" method="post" action="<?= Helpers::e(Helpers::url('/wallets/update/' . (int)$w['id'])) ?>">
                  <?= Helpers::csrfField() ?>
                  <div class="grid sm:grid-cols-2 gap-2">
                    <input name="label" value="<?= Helpers::e($w['label']) ?>" class="px-2 py-1 rounded border bg-white dark:bg-gray-900" />
                    <input name="chain" value="<?= Helpers::e($w['chain']) ?>" class="px-2 py-1 rounded border bg-white dark:bg-gray-900" />
                    <input name="address" value="<?= Helpers::e($w['address']) ?>" class="px-2 py-1 rounded border bg-white dark:bg-gray-900 sm:col-span-2" />
                    <input name="payment_uri" value="<?= Helpers::e($w['payment_uri']) ?>" placeholder="tron:..." class="px-2 py-1 rounded border bg-white dark:bg-gray-900 sm:col-span-2" />
                  </div>
                  <div class="mt-2 flex items-center gap-2">
                    <label class="inline-flex items-center gap-1 text-xs"><input type="checkbox" name="is_visible" <?= $w['is_visible']? 'checked':'' ?>> Visible</label>
                    <button class="px-2 py-1 text-xs rounded border">Save</button>
                    <button type="button" class="px-2 py-1 text-xs rounded border" data-cancel>Done</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" class="px-2 py-1 text-xs rounded border" data-edit>Edit</button>
              <form method="post" action="<?= Helpers::e(Helpers::url('/wallets/delete/' . (int)$w['id'])) ?>" onsubmit="return confirm('Delete wallet?')">
                <?= Helpers::csrfField() ?>
                <button class="px-2 py-1 text-xs rounded border">Delete</button>
              </form>
            </div>
          </div>
        <?php endforeach; endif; ?>
        <?php if (!empty($links)): foreach ($links as $l): ?>
          <div class="ui-item p-3 flex items-center justify-between gap-3" data-link-row>
            <div class="min-w-0 flex items-center gap-2">
              <?php $iconKey = match(strtolower($l['type'] ?? 'link')){ 'paypal'=>'paypal','binance'=>'bnb','usdt_trc20'=>'usdt','cashapp'=>'cashapp','patreon'=>'patreon','buymeacoffee'=>'buymeacoffee','kofi'=>'kofi','stripe'=>'stripe', default=>'link'}; echo App\Lib\Helpers::brandIconImg($iconKey); ?>
              <div class="min-w-0">
                <div class="font-medium truncate" data-view><?= Helpers::e($l['label']) ?></div>
                <div class="text-xs text-gray-500 truncate" data-view><?= Helpers::e($l['url']) ?></div>
                <form class="inline-edit hidden mt-2" method="post" action="<?= Helpers::e(Helpers::url('/links/update/' . (int)$l['id'])) ?>">
                  <?= Helpers::csrfField() ?>
                  <div class="grid sm:grid-cols-2 gap-2">
                    <input name="label" value="<?= Helpers::e($l['label']) ?>" class="px-2 py-1 rounded border bg-white dark:bg-gray-900" />
                    <input name="url" value="<?= Helpers::e($l['url']) ?>" class="px-2 py-1 rounded border bg-white dark:bg-gray-900" />
                  </div>
                  <input type="hidden" name="type" value="<?= Helpers::e($l['type']) ?>">
                  <input type="hidden" name="is_visible" value="<?= (int)$l['is_visible'] ?>">
                  <div class="mt-2 flex items-center gap-2">
                    <button class="px-2 py-1 text-xs rounded border">Save</button>
                    <button type="button" class="px-2 py-1 text-xs rounded border" data-cancel>Done</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button type="button" class="px-2 py-1 text-xs rounded border" data-edit>Edit</button>
              <form method="post" action="<?= Helpers::e(Helpers::url('/links/delete/' . (int)$l['id'])) ?>" onsubmit="return confirm('Delete link?')">
                <?= Helpers::csrfField() ?>
                <button class="px-2 py-1 text-xs rounded border">Delete</button>
              </form>
            </div>
          </div>
        <?php endforeach; endif; ?>
        <?php if (empty($wallets) && empty($links)): ?>
          <div class="text-sm text-gray-500">No payments yet. Add one to see it here.</div>
        <?php endif; ?>
      </div>
    </div>
    <form method="post" action="<?= Helpers::e(Helpers::url('/dashboard/quick-add')) ?>" class="grid gap-4 hidden" id="legacyForm">
      <?= Helpers::csrfField() ?>
      <?php $options=[
        ['key'=>'usdt_trc20','label'=>'USDT (TRC-20)','kind'=>'wallet','chain'=>'TRON','type'=>null,'url_ph'=>'Enter TRC-20 address','uri_ph'=>'tron:...'],
        ['key'=>'btc','label'=>'BTC','kind'=>'wallet','chain'=>'BITCOIN','type'=>null,'url_ph'=>'Enter BTC address','uri_ph'=>'bitcoin:...'],
        ['key'=>'eth','label'=>'ETH','kind'=>'wallet','chain'=>'ETH','type'=>null,'url_ph'=>'Enter ETH address','uri_ph'=>'ethereum:...'],
        ['key'=>'trx','label'=>'TRX','kind'=>'wallet','chain'=>'TRON','type'=>null,'url_ph'=>'Enter TRX address','uri_ph'=>'tron:...'],
        ['key'=>'paypal','label'=>'PayPal','kind'=>'link','chain'=>'paypal','type'=>'paypal','url_ph'=>'https://paypal.me/yourname','uri_ph'=>null],
        ['key'=>'binance','label'=>'Binance Pay','kind'=>'link','chain'=>'binance','type'=>'binance','url_ph'=>'https://pay.binance.com/...','uri_ph'=>null],
        ['key'=>'custom','label'=>'Custom link','kind'=>'link','chain'=>'','type'=>'custom','url_ph'=>'https://example.com','uri_ph'=>null],
      ]; ?>
      <div class="grid gap-4">
        <?php for($i=0;$i<5;$i++): $preset=$options[$i]??$options[6]; ?>
        <fieldset class="p-4 rounded-xl border border-gray-200 dark:border-gray-700" data-row="<?= $i ?>">
          <div class="flex items-center justify-between gap-3 mb-3">
            <div class="flex items-center gap-2">
              <button type="button" class="px-3 py-1.5 rounded-lg border flex items-center gap-2" data-open-menu data-row="<?= $i ?>">
                <span class="icon"><?= App\Lib\Helpers::brandIconImg(match($preset['key']){'usdt_trc20'=>'usdt','btc'=>'btc','eth'=>'eth','trx'=>'tron','paypal'=>'paypal','binance'=>'bnb',default=>'link'}) ?></span>
                <span class="label"><?= Helpers::e($preset['label']) ?></span>
              </button>
              <div class="relative">
                <div class="absolute mt-1 w-64 hidden z-10 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-soft p-2" data-menu data-row="<?= $i ?>">
                  <div class="grid grid-cols-2 gap-2">
                    <?php foreach($options as $o): ?>
                      <button type="button" class="px-3 py-2 rounded-lg border hover:bg-gray-50 dark:hover:bg-gray-800 flex items-center gap-2" data-apply data-row="<?= $i ?>"
                        data-key="<?= Helpers::e($o['key']) ?>" data-label="<?= Helpers::e($o['label']) ?>" data-kind="<?= Helpers::e($o['kind']) ?>" data-chain="<?= Helpers::e($o['chain']) ?>" data-type="<?= Helpers::e($o['type']??'') ?>" data-url-ph="<?= Helpers::e($o['url_ph']??'') ?>" data-uri-ph="<?= Helpers::e($o['uri_ph']??'') ?>">
                        <span class="icon"><?= App\Lib\Helpers::brandIconImg(match($o['key']){'usdt_trc20'=>'usdt','btc'=>'btc','eth'=>'eth','trx'=>'tron','paypal'=>'paypal','binance'=>'bnb',default=>'link'}) ?></span>
                        <span><?= Helpers::e($o['label']) ?></span>
                      </button>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
            <label class="inline-flex items-center gap-2"><input type="checkbox" name="rows[<?= $i ?>][enabled]" value="1" class="scale-110"> Enable</label>
          </div>
          <input type="hidden" name="rows[<?= $i ?>][kind]" value="<?= Helpers::e($preset['kind']) ?>" data-kind>
          <input type="hidden" name="rows[<?= $i ?>][type]" value="<?= Helpers::e($preset['type']??'custom') ?>" data-type>
          <div class="grid sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm mb-1">Label</label>
              <input name="rows[<?= $i ?>][label]" value="<?= Helpers::e($preset['label']) ?>" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" data-label />
            </div>
            <div>
              <label class="block text-sm mb-1">Chain / Link Type</label>
              <input name="rows[<?= $i ?>][chain]" value="<?= Helpers::e($preset['chain']) ?>" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" data-chain />
            </div>
          </div>
          <div class="grid sm:grid-cols-2 gap-3 mt-3">
            <div>
              <label class="block text-sm mb-1" data-url-label>URL / Address</label>
              <input name="rows[<?= $i ?>][url]" placeholder="<?= Helpers::e($preset['url_ph']) ?>" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" data-url />
            </div>
            <div data-wallet-only>
              <label class="block text-sm mb-1">Payment URI (optional)</label>
              <input name="rows[<?= $i ?>][payment_uri]" placeholder="<?= Helpers::e($preset['uri_ph']??'') ?>" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" data-uri />
            </div>
          </div>
          <div class="mt-2 text-xs text-gray-500">For wallets, paste your address. URI is optional and used for QR apps.</div>
        </fieldset>
        <?php endfor; ?>
      </div>
      <div class="flex items-center gap-3">
        <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Save payments</button>
      </div>
    </form>
  </div>
</div>
<?php $csrfField = App\Lib\Helpers::csrfField(); ?>
<div id="paymentsModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="paymentsTitle">
  <div class="absolute inset-0 bg-black/50" data-close></div>
  <div class="relative mx-auto my-10 w-full max-w-3xl bg-white dark:bg-gray-900 rounded-2xl shadow-soft border border-gray-200 dark:border-gray-800">
    <form id="paymentsForm" method="post" action="<?= Helpers::e(Helpers::url('/dashboard/quick-add')) ?>">
      <div class="p-5 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h3 id="paymentsTitle" class="text-lg font-semibold">Choose payment method</h3>
        <div class="flex items-center gap-2">
          <button type="button" id="backToPicker" class="px-2 py-1 rounded border border-gray-300 dark:border-gray-700 hidden">Back</button>
          <button type="button" data-close class="px-2 py-1 rounded border border-gray-300 dark:border-gray-700">Close</button>
        </div>
      </div>
      <div class="p-5 grid md:grid-cols-3 gap-3">
        <?php $opts=[
          ['key'=>'usdt_trc20','label'=>'USDT (TRC-20)','kind'=>'wallet','chain'=>'TRON','type'=>null,'url_ph'=>'Enter TRC-20 address','uri_ph'=>'tron:...','icon'=>App\Lib\Helpers::brandIconImg('usdt')],
          ['key'=>'btc','label'=>'BTC','kind'=>'wallet','chain'=>'BITCOIN','type'=>null,'url_ph'=>'Enter BTC address','uri_ph'=>'bitcoin:...','icon'=>App\Lib\Helpers::brandIconImg('btc')],
          ['key'=>'eth','label'=>'ETH','kind'=>'wallet','chain'=>'ETH','type'=>null,'url_ph'=>'Enter ETH address','uri_ph'=>'ethereum:...','icon'=>App\Lib\Helpers::brandIconImg('eth')],
          ['key'=>'trx','label'=>'TRX','kind'=>'wallet','chain'=>'TRON','type'=>null,'url_ph'=>'Enter TRX address','uri_ph'=>'tron:...','icon'=>App\Lib\Helpers::brandIconImg('tron')],
          ['key'=>'ltc','label'=>'LTC','kind'=>'wallet','chain'=>'LITECOIN','type'=>null,'url_ph'=>'Enter LTC address','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('ltc')],
          ['key'=>'doge','label'=>'DOGE','kind'=>'wallet','chain'=>'DOGE','type'=>null,'url_ph'=>'Enter DOGE address','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('doge')],
          ['key'=>'xrp','label'=>'XRP','kind'=>'wallet','chain'=>'XRP','type'=>null,'url_ph'=>'Enter XRP address','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('xrp')],
          ['key'=>'ada','label'=>'ADA','kind'=>'wallet','chain'=>'CARDANO','type'=>null,'url_ph'=>'Enter ADA address','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('ada')],
          ['key'=>'matic','label'=>'MATIC','kind'=>'wallet','chain'=>'POLYGON','type'=>null,'url_ph'=>'Enter MATIC address','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('matic')],
          ['key'=>'paypal','label'=>'PayPal','kind'=>'link','chain'=>'paypal','type'=>'paypal','url_ph'=>'https://paypal.me/yourname','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('paypal')],
          ['key'=>'binance','label'=>'Binance Pay','kind'=>'link','chain'=>'binance','type'=>'binance','url_ph'=>'https://pay.binance.com/...','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('bnb')],
          ['key'=>'cashapp','label'=>'Cash App','kind'=>'link','chain'=>'cashapp','type'=>'other','url_ph'=>'https://cash.app/$yourname','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('cashapp')],
          ['key'=>'patreon','label'=>'Patreon','kind'=>'link','chain'=>'patreon','type'=>'other','url_ph'=>'https://patreon.com/yourname','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('patreon')],
          ['key'=>'buymeacoffee','label'=>'Buy Me a Coffee','kind'=>'link','chain'=>'buymeacoffee','type'=>'other','url_ph'=>'https://buymeacoffee.com/yourname','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('buymeacoffee')],
          ['key'=>'kofi','label'=>'Ko-fi','kind'=>'link','chain'=>'kofi','type'=>'other','url_ph'=>'https://ko-fi.com/yourname','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('kofi')],
          ['key'=>'stripe','label'=>'Stripe Payment Link','kind'=>'link','chain'=>'stripe','type'=>'other','url_ph'=>'https://buy.stripe.com/...','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('stripe')],
          ['key'=>'custom','label'=>'Custom link','kind'=>'link','chain'=>'','type'=>'custom','url_ph'=>'https://example.com','uri_ph'=>null,'icon'=>App\Lib\Helpers::brandIconImg('link')],
        ]; foreach($opts as $o): ?>
          <button type="button" class="p-3 rounded-xl border hover:bg-gray-50 dark:hover:bg-gray-800 text-left flex items-center gap-3" data-pick='<?= json_encode($o, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>'>
            <span class="shrink-0"><?= $o['icon'] ?></span>
            <span class="font-medium"><?= Helpers::e($o['label']) ?></span>
          </button>
        <?php endforeach; ?>
      </div>
      <div id="methodFields" class="p-5 hidden">
        <div class="flex items-center gap-2 mb-3"><span id="methodIcon"></span><div id="methodLabel" class="font-medium"></div></div>
        <input type="hidden" name="rows[0][enabled]" value="1">
        <input type="hidden" name="rows[0][kind]" id="fieldKind" value="link">
        <input type="hidden" name="rows[0][type]" id="fieldType" value="custom">
        <div class="grid sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-sm mb-1">Label</label>
            <input name="rows[0][label]" id="fieldLabel" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
          </div>
          <div>
            <label class="block text-sm mb-1">Chain / Link Type</label>
            <input name="rows[0][chain]" id="fieldChain" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
          </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-3 mt-3">
          <div>
            <label class="block text-sm mb-1" id="urlLabel">URL / Address</label>
            <input name="rows[0][url]" id="fieldUrl" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
          </div>
          <div id="uriWrap">
            <label class="block text-sm mb-1">Payment URI (optional)</label>
            <input name="rows[0][payment_uri]" id="fieldUri" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
          </div>
        </div>
        <div class="mt-3">
          <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="rows[0][is_visible]" value="1" checked class="scale-110"> Visible</label>
        </div>
      </div>
      <div class="p-5 border-t border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <div id="modalHint" class="text-sm text-gray-500">Pick a method to continue</div>
        <div class="flex items-center gap-3">
          <button type="button" id="footerBackBtn" class="px-3 py-2 rounded border">Back</button>
          <button id="savePaymentsBtn" class="px-4 py-2 rounded bg-primary-600 text-white hover:bg-primary-700" disabled>Save</button>
        </div>
      </div>
      <?= $csrfField ?>
    </form>
  </div>
</div>
<!-- Removed legacy JS builder to avoid accidental visible hash tokens; list is server-rendered above -->

<script>
  // Fallback one-by-one flow: when a method is clicked, show a single-entry form
  (function(){
    const modal = document.getElementById('paymentsModal');
    const saveBtn = document.getElementById('savePaymentsBtn');
    const form = document.getElementById('paymentsForm');
    const openBtn = document.getElementById('openPaymentsModal');
    if(!modal || !form) return;
    function ensureOneByOne(){
      let container = document.getElementById('oneByOne');
      if(!container){
        container = document.createElement('div');
        container.id = 'oneByOne';
        container.className = 'p-5 hidden';
        form.appendChild(container);
      }
      return container;
    }
    function resetModal(){
      const grid = modal.querySelector('[data-pick]')?.closest('.p-5');
      const selectedWrap = document.getElementById('selectedList')?.parentElement;
      const container = document.getElementById('oneByOne');
      if (grid) grid.classList.remove('hidden');
      if (selectedWrap) selectedWrap.classList.add('hidden');
      if (container) { container.innerHTML=''; container.classList.add('hidden'); }
      const title=document.getElementById('paymentsTitle'); if(title) title.textContent='Choose payment method';
      const hint=document.getElementById('modalHint'); if(hint) hint.textContent='Pick a method to continue';
      if(saveBtn) saveBtn.disabled = true;
    }
    function showForm(data){
      const grid = modal.querySelector('[data-pick]')?.closest('.p-5');
      const selectedWrap = document.getElementById('selectedList')?.parentElement;
      const container = ensureOneByOne();
      if(grid) grid.classList.add('hidden');
      if(selectedWrap) selectedWrap.classList.add('hidden');
      container.innerHTML = `
        <div class="flex items-center gap-2 mb-3"><span>${data.icon||''}</span><div class="font-medium">${data.label||''}</div></div>
        <input type="hidden" name="rows[0][enabled]" value="1">
        <input type="hidden" name="rows[0][kind]" value="${data.kind||'link'}">
        <input type="hidden" name="rows[0][type]" value="${data.type||'custom'}">
        <div class="grid sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-sm mb-1">Label</label>
            <input name="rows[0][label]" value="${data.label||''}" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
          </div>
          <div>
            <label class="block text-sm mb-1">Chain / Link Type</label>
            <input name="rows[0][chain]" value="${data.chain||''}" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
          </div>
        </div>
        <div class="grid sm:grid-cols-2 gap-3 mt-3">
          <div>
            <label class="block text-sm mb-1">URL / Address</label>
            <input name="rows[0][url]" placeholder="${data.url_ph||''}" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
          </div>
          ${(data.kind==='wallet')? `<div><label class=\"block text-sm mb-1\">Payment URI (optional)</label><input name=\"rows[0][payment_uri]\" placeholder=\"${data.uri_ph||''}\" class=\"w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900\" /></div>` : `<div></div>`}
        </div>
        <div class="mt-3">
          <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="rows[0][is_visible]" value="1" checked class="scale-110"> Visible</label>
        </div>
      `;
      container.classList.remove('hidden');
      if(saveBtn) saveBtn.disabled = false;
    }
    modal.querySelectorAll('[data-pick]')?.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const data = JSON.parse(btn.getAttribute('data-pick')) || {};
        data.icon = btn.querySelector('span')?.innerHTML || '';
        showForm(data);
      });
    });
    // Reset when opening/closing to ensure a clean state
    openBtn?.addEventListener('click', () => { resetModal(); modal.classList.remove('hidden'); });
    modal.querySelectorAll('[data-close]')?.forEach(b=> b.addEventListener('click', () => { resetModal(); modal.classList.add('hidden'); }));
    // Footer Back button behavior
    document.getElementById('footerBackBtn')?.addEventListener('click', ()=>{
      const one = document.getElementById('oneByOne');
      const grid = modal.querySelector('[data-pick]')?.closest('.p-5');
      if(one && !one.classList.contains('hidden')){
        one.classList.add('hidden'); if(grid) grid.classList.remove('hidden'); if(saveBtn) saveBtn.disabled = true; const hint=document.getElementById('modalHint'); if(hint) hint.textContent='Pick a method to continue'; const title=document.getElementById('paymentsTitle'); if(title) title.textContent='Choose payment method';
      } else {
        resetModal(); modal.classList.add('hidden');
      }
    });

    // Inline edit toggles for list items
    document.querySelectorAll('[data-edit]')?.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const row = btn.closest('[data-wallet-row],[data-link-row]');
        if(!row) return;
        row.querySelectorAll('[data-view]')?.forEach(el=> el.classList.add('hidden'));
        row.querySelector('.inline-edit')?.classList.remove('hidden');
      });
    });
    document.querySelectorAll('[data-cancel]')?.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const row = btn.closest('[data-wallet-row],[data-link-row]');
        if(!row) return;
        row.querySelectorAll('[data-view]')?.forEach(el=> el.classList.remove('hidden'));
        row.querySelector('.inline-edit')?.classList.add('hidden');
      });
    });
  })();
</script>

