<?php use App\Lib\Helpers; ?>
<div class="max-w-2xl mx-auto">
  <div class="ui-card rounded-2xl p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Profile</h3>
      <a href="<?= Helpers::e(Helpers::url('/' . $user['username'])) ?>" target="_blank" class="text-sm text-blue-400 hover:text-blue-300">View page</a>
    </div>

    <div class="flex flex-col items-center gap-4 mb-5">
      <img src="<?= Helpers::e($user['avatar'] ? (str_starts_with($user['avatar'],'http') ? $user['avatar'] : Helpers::url(ltrim($user['avatar'],'/'))) : ('https://www.gravatar.com/avatar/' . md5(strtolower(trim($user['email']))) . '?s=160&d=mp')) ?>" class="w-24 h-24 rounded-full object-cover border" alt="Avatar">
      <form method="post" action="<?= Helpers::e(Helpers::url('/profile/avatar')) ?>" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-2">
        <?= Helpers::csrfField() ?>
        <input type="file" name="avatar" accept="image/png,image/jpeg,image/gif,image/webp" class="text-sm" required />
        <button class="px-4 py-2 rounded-xl border">Upload</button>
      </form>
      <div class="text-xs text-gray-500">PNG, JPG, GIF, or WEBP up to 4MB</div>
    </div>

    <form method="post" action="<?= Helpers::e(Helpers::url('/profile/update')) ?>" class="space-y-4">
      <?= Helpers::csrfField() ?>
      <div>
        <label class="block text-sm mb-1">Display name</label>
        <input name="display_name" value="<?= Helpers::e($user['display_name']) ?>" placeholder="How should we call you?" class="w-full px-3 py-2 rounded-xl border bg-white dark:bg-gray-900" required />
        <p class="mt-1 text-xs text-gray-500">Shown at the top of your public page.</p>
      </div>
      <div>
        <div class="flex items-center justify-between">
          <label class="block text-sm mb-1">Bio</label>
          <span id="bioCount" class="text-xs text-gray-500">0/160</span>
        </div>
        <textarea name="bio" id="bioInput" class="w-full px-3 py-2 rounded-xl border bg-white dark:bg-gray-900" maxlength="160" rows="3" placeholder="A short line about you..."><?= Helpers::e($user['bio']) ?></textarea>
      </div>
      <div>
        <label class="block text-sm mb-1">Username</label>
        <div class="flex rounded-xl border overflow-hidden">
          <span class="px-3 py-2 bg-gray-800 text-white border-r border-gray-200 dark:border-gray-700 select-none shrink-0">/</span>
          <input name="username" id="usernameInput" value="<?= Helpers::e($user['username']) ?>" pattern="[A-Za-z0-9_]{3,32}" class="w-full px-3 py-2 bg-white dark:bg-gray-900" required />
        </div>
        <div class="mt-1 text-xs text-gray-500">URL: <a id="usernamePreview" class="underline" target="_blank" href="<?= Helpers::e(Helpers::url('/' . $user['username'])) ?>"><?= Helpers::e(Helpers::url('/' . $user['username'])) ?></a></div>
      </div>
      <div class="flex items-center gap-3 pt-2">
        <button class="px-4 py-2 rounded-xl bg-primary-600 hover:bg-primary-700 btn-pink-black">Save changes</button>
        <button type="button" class="px-4 py-2 rounded-xl border" id="copyPublicUrl">Copy public URL</button>
      </div>
    </form>
  </div>
</div>
<script>
  // Bio counter
  const bio = document.getElementById('bioInput'); const bioCount = document.getElementById('bioCount');
  function updateBio(){ if(!bio||!bioCount) return; bioCount.textContent = `${bio.value.length}/160`; }
  bio?.addEventListener('input', updateBio); updateBio();
  // Live URL previews and copy
  const uBase = <?= json_encode(Helpers::url('/')) ?>.replace(/\/$/, '');
  const usernameInput = document.getElementById('usernameInput');
  const usernamePreview = document.getElementById('usernamePreview');
  function syncPreviews(){ if(usernameInput&&usernamePreview){ const url = `${uBase}/${(usernameInput.value||'').trim()}`; usernamePreview.href=url; usernamePreview.textContent=url; } }
  usernameInput?.addEventListener('input', syncPreviews);
  syncPreviews();
  document.getElementById('copyPublicUrl')?.addEventListener('click', async()=>{
    try{ const url = usernamePreview?.href || `${uBase}/<?= Helpers::e($user['username']) ?>`; await navigator.clipboard.writeText(url); showToast('URL copied'); }catch(e){}
  });
</script>
