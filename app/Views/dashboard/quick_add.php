<?php use App\Lib\Helpers; ?>
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
  <h3 class="text-lg font-semibold mb-4">Quick add payments (up to 5)</h3>
  <form method="post" action="<?= Helpers::e(Helpers::url('/dashboard/quick-add')) ?>" class="grid gap-4">
    <?= Helpers::csrfField() ?>
    <div class="grid gap-4">
      <?php for($i=0;$i<5;$i++): ?>
        <fieldset class="p-4 rounded-xl border border-gray-200 dark:border-gray-700">
          <legend class="text-sm text-gray-600 dark:text-gray-400">Item <?= $i+1 ?></legend>
          <label class="inline-flex items-center gap-2 mb-2"><input type="checkbox" name="rows[<?= $i ?>][enabled]" value="1" class="scale-110"> Enable</label>
          <div class="grid sm:grid-cols-4 gap-3 items-end">
            <div>
              <label class="block text-sm mb-1">Type</label>
              <select name="rows[<?= $i ?>][kind]" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900">
                <option value="wallet">Wallet</option>
                <option value="link">Link</option>
              </select>
            </div>
            <div>
              <label class="block text-sm mb-1">Label</label>
              <input name="rows[<?= $i ?>][label]" placeholder="USDT (TRC-20) or PayPal" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
            </div>
            <div>
              <label class="block text-sm mb-1">URL / Address</label>
              <input name="rows[<?= $i ?>][url]" placeholder="https://... or wallet address" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
            </div>
            <div>
              <label class="block text-sm mb-1">Chain / Type</label>
              <input name="rows[<?= $i ?>][chain]" placeholder="TRON / paypal" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
            </div>
            <div class="sm:col-span-2">
              <label class="block text-sm mb-1">Payment URI (optional)</label>
              <input name="rows[<?= $i ?>][payment_uri]" placeholder="tron:..." class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900" />
            </div>
            <div>
              <label class="inline-flex items-center gap-2"><input type="checkbox" name="rows[<?= $i ?>][is_visible]" value="1" checked class="scale-110"> Visible</label>
            </div>
            <div>
              <label class="block text-sm mb-1">Link Type</label>
              <select name="rows[<?= $i ?>][type]" class="w-full px-3 py-2 rounded-lg border bg-white dark:bg-gray-900">
                <option value="custom">Custom</option>
                <option value="paypal">PayPal</option>
                <option value="binance">Binance</option>
                <option value="usdt_trc20">USDT TRC-20</option>
                <option value="guide">Guide</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
        </fieldset>
      <?php endfor; ?>
    </div>
    <div class="flex items-center gap-3">
      <button class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Add</button>
      <a href="<?= Helpers::e(Helpers::url('/dashboard')) ?>" class="px-4 py-2 rounded-lg border">Cancel</a>
    </div>
  </form>
</div>

