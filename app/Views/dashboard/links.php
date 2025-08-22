<?php use App\Lib\Helpers; ?>
<div class="grid lg:grid-cols-2 gap-6">
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
    <h3 class="text-lg font-semibold mb-4">Add link</h3>
    <form method="post" action="<?= Helpers::e(Helpers::url('/links/create')) ?>" class="grid gap-4">
      <?= Helpers::csrfField() ?>
      <?php $platforms=[ ['key'=>'paypal','label'=>'PayPal','placeholder'=>'https://paypal.me/yourname'], ['key'=>'binance','label'=>'Binance Pay','placeholder'=>'https://pay.binance.com/...'], ['key'=>'usdt_trc20','label'=>'USDT TRC-20','placeholder'=>'https://... or your own link'], ['key'=>'custom','label'=>'Custom','placeholder'=>'https://example.com'] ]; ?>
      <div>
        <label class="block text-sm mb-1">Platform</label>
        <div class="relative">
          <button type="button" id="platformMenuBtn" class="w-full flex items-center justify-between px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
            <span class="flex items-center gap-2">
              <?= App\Lib\Helpers::brandIconImg('paypal') ?>
              <span id="platformMenuLabel">PayPal</span>
            </span>
            <svg class="h-4 w-4 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
          </button>
          <div id="platformMenu" class="absolute mt-1 w-full hidden z-10 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-soft">
            <ul class="max-h-72 overflow-auto py-1">
              <?php foreach($platforms as $p): ?>
                <li>
                  <button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 flex items-center gap-2" data-platform='<?= json_encode($p, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>'>
                    <?= App\Lib\Helpers::brandIconImg($p['key']) ?>
                    <span><?= Helpers::e($p['label']) ?></span>
                  </button>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
      <input type="hidden" name="label" value="PayPal" />
      <div>
        <label class="block text-sm mb-1">URL</label>
        <input name="url" type="url" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" placeholder="https://paypal.me/yourname" required />
      </div>
      <div class="grid sm:grid-cols-2 gap-3">
        <div>
          <label class="block text-sm mb-1">Type</label>
          <input name="type" value="paypal" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" readonly />
        </div>
        <div class="flex items-end">
          <label class="inline-flex items-center gap-2"><input type="checkbox" name="is_visible" checked class="scale-110"> Visible</label>
        </div>
      </div>
      <button class="justify-self-start px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Add</button>
    </form>
  </div>
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Your links</h3>
      <span class="text-xs text-gray-500">Drag to reorder</span>
    </div>
    <ul id="linkList" class="space-y-3" aria-live="polite">
      <?php foreach ($links as $link): ?>
        <li class="p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 flex items-center justify-between gap-3" draggable="true" data-id="<?= (int)$link['id'] ?>">
          <div class="flex items-center gap-3 min-w-0">
            <?php 
              $type = strtolower($link['type']);
              $badge = match($type){
                'paypal' => App\Lib\Helpers::iconBadge('paypal'),
                'binance' => App\Lib\Helpers::iconBadge('bnb'),
                'usdt_trc20' => App\Lib\Helpers::iconBadge('usdt'),
                default => App\Lib\Helpers::iconBadge('link'),
              };
            ?>
            <?= $badge ?>
            <div class="min-w-0">
              <div class="font-medium truncate flex items-center gap-2">
                <?= Helpers::e($link['label']) ?>
                <?php if ((int)($user['primary_link_id'] ?? 0) === (int)$link['id']): ?>
                  <span class="text-[10px] px-2 py-0.5 bg-green-600 text-white rounded">Primary</span>
                <?php endif; ?>
              </div>
              <div class="text-xs text-gray-500 truncate"><?= Helpers::e($link['url']) ?></div>
            </div>
          </div>
          <div class="flex items-center gap-2 shrink-0">
            <form method="post" action="<?= Helpers::e(Helpers::url('/links/update/' . (int)$link['id'])) ?>" class="flex items-center gap-2">
              <?= Helpers::csrfField() ?>
              <input type="hidden" name="label" value="<?= Helpers::e($link['label']) ?>">
              <input type="hidden" name="url" value="<?= Helpers::e($link['url']) ?>">
              <input type="hidden" name="type" value="<?= Helpers::e($link['type']) ?>">
              <input type="hidden" name="is_visible" value="<?= (int)$link['is_visible'] ?>">
              <button name="set_primary" value="1" class="px-2 py-1 text-xs rounded border">Make Primary</button>
            </form>
            <form method="post" action="<?= Helpers::e(Helpers::url('/links/delete/' . (int)$link['id'])) ?>" onsubmit="return confirm('Delete link?')">
              <?= Helpers::csrfField() ?>
              <button class="px-2 py-1 text-xs rounded border">Delete</button>
            </form>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<script>
  // Platform dropdown behavior
  (function(){
    const btn = document.getElementById('platformMenuBtn');
    const menu = document.getElementById('platformMenu');
    const label = document.querySelector('input[name="label"]');
    const type = document.querySelector('input[name="type"]');
    const url = document.querySelector('input[name="url"]');
    const btnText = document.getElementById('platformMenuLabel');
    function close(){ menu?.classList.add('hidden'); document.removeEventListener('click', onDoc, true); }
    function onDoc(e){ if(!menu.contains(e.target) && !btn.contains(e.target)) close(); }
    btn?.addEventListener('click', ()=>{ menu?.classList.toggle('hidden'); if(!menu.classList.contains('hidden')) setTimeout(()=>document.addEventListener('click', onDoc, true),0); });
    menu?.querySelectorAll('[data-platform]')?.forEach(el=>{
      el.addEventListener('click', ()=>{
        try{
          const data = JSON.parse(el.getAttribute('data-platform'));
          if(label) label.value = data.label || '';
          if(type) type.value = data.key || 'custom';
          if(url) { url.placeholder = data.placeholder || ''; url.value=''; }
          if(btnText) btnText.textContent = data.label || '';
        }catch(err){}
        close();
      });
    });
  })();
  const list=document.getElementById('linkList'); let dragEl;
  list?.addEventListener('dragstart',e=>{dragEl=e.target.closest('[draggable]'); e.dataTransfer.effectAllowed='move';});
  list?.addEventListener('dragover',e=>{e.preventDefault(); const after=[...list.querySelectorAll('[draggable]')].find(li=>{const r=li.getBoundingClientRect(); return e.clientY<r.top+r.height/2;}); if(after) list.insertBefore(dragEl,after); else list.appendChild(dragEl);});
  list?.addEventListener('drop',()=>{ const order=[...list.querySelectorAll('[draggable]')].map(li=>li.dataset.id); fetch('<?= Helpers::e(Helpers::url('/links/reorder')) ?>',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:new URLSearchParams({ _token:'<?= Helpers::e(Helpers::csrfToken()) ?>', order: order })}); showToast('Order saved'); });
</script>
