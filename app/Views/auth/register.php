<?php use App\Lib\Helpers; ?>
<section class="min-h-[70vh] grid place-items-center">
  <div class="w-full max-w-md ui-card rounded-3xl p-8">
    <div class="flex items-center gap-3 mb-4"><img src="<?= Helpers::e(Helpers::url('/img/logo.webp')) ?>" alt="Logo" class="h-8 w-8 rounded-xl object-contain" /><h2 class="text-2xl font-semibold">Create your account</h2></div>
    <form method="post" action="<?= Helpers::e(Helpers::url('/register')) ?>" class="grid gap-4">
      <?= Helpers::csrfField() ?>
      <label class="grid gap-1 text-sm">Username
        <input type="text" name="username" required pattern="[A-Za-z0-9_]{3,32}" class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" />
      </label>
      <label class="grid gap-1 text-sm">Email
        <input type="email" name="email" required class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" />
      </label>
      <label class="grid gap-1 text-sm">Display name
        <input type="text" name="display_name" required class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" />
      </label>
      <label class="grid gap-1 text-sm">Password
        <input type="password" name="password" required minlength="8" class="mt-1 w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" />
      </label>
      <label class="grid gap-1 text-sm">Solve this Noob Equation
        <div class="mt-1 flex items-center gap-2">
          <input type="text" value="<?= isset($registerCaptchaQ)? Helpers::e($registerCaptchaQ):'Solve the math' ?>" class="flex-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" disabled />
          <input type="number" name="captcha" required placeholder="Answer" class="w-28 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" />
        </div>
      </label>
      <button class="mt-2 w-full py-3 bg-primary-600 text-white rounded-xl hover:bg-primary-700">Create account</button>
    </form>
    <p class="text-sm text-gray-400 mt-4">Already have an account? <a class="text-primary-600" href="<?= Helpers::e(Helpers::url('/login')) ?>">Login</a></p>
  </div>
</section>
