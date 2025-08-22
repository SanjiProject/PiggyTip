<?php use App\Lib\Helpers; ?>
<div class="grid lg:grid-cols-2 gap-6">
  <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-soft p-6">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-semibold">Your public page</h3>
      <div class="text-xs text-gray-500">Quick actions</div>
    </div>
    <div class="flex items-center justify-between mt-1">
      <a class="text-primary-600" target="_blank" href="<?= Helpers::e(Helpers::url('/' . $user['username'])) ?>"><?= Helpers::e('/' . $user['username']) ?></a>
    </div>
    <div class="mt-4 flex flex-wrap gap-3">
      <a href="<?= Helpers::e(Helpers::url('/dashboard/payments')) ?>" class="px-3 py-2 rounded-lg border hover:bg-gray-50 dark:hover:bg-gray-700">Add payment</a>
      <a href="<?= Helpers::e(Helpers::url('/dashboard/profile')) ?>" class="px-3 py-2 rounded-lg border hover:bg-gray-50 dark:hover:bg-gray-700">Edit profile</a>
      <a href="<?= Helpers::e(Helpers::url('/' . $user['username'])) ?>" target="_blank" class="px-3 py-2 rounded-lg border hover:bg-gray-50 dark:hover:bg-gray-700">View page</a>
    </div>
    <div class="mt-6 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-2xl p-4">
      <div class="font-medium mb-1 text-white">Get started</div>
      <ol class="list-decimal list-inside text-sm text-white space-y-1">
        <li>Add USDT or other wallets</li>
        <li>Add PayPal, Binance Pay, Guide or any external link</li>
        <li>Mark one as Primary to power the Donate button</li>
        <li>Share your public URL</li>
      </ol>
    </div>
  </div>
  <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-soft p-6">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-lg font-semibold">Last 7 days</h3>
      <div class="flex items-center gap-4 text-sm text-gray-400">
        <div class="flex items-center gap-2"><span class="inline-block h-2 w-2 rounded-full" style="background:#2563eb"></span>Views <span id="viewsTotal" class="text-gray-200 font-medium"></span></div>
        <div class="flex items-center gap-2"><span class="inline-block h-2 w-2 rounded-full" style="background:#16a34a"></span>Clicks <span id="clicksTotal" class="text-gray-200 font-medium"></span></div>
      </div>
    </div>
    <div class="relative h-56 md:h-64">
      <canvas id="chart" class="w-full h-full"></canvas>
      <div id="chartEmpty" class="absolute inset-0 hidden items-center justify-center text-gray-500">No data yet</div>
    </div>
  </div>
</div>
<script>
  const summary = <?= json_encode($summary, JSON_UNESCAPED_SLASHES) ?>;
  // Build last 7 days labels and datasets, padding missing days with zeros
  const days = 7;
  const map = new Map(summary.map(x => [x.d, { views: Number(x.views||0), clicks: Number(x.clicks||0) }]));
  const labels = [];
  const viewsData = [];
  const clicksData = [];
  for (let i = days - 1; i >= 0; i--) {
    const d = new Date(); d.setHours(12,0,0,0); d.setDate(d.getDate() - i);
    const key = d.toISOString().slice(0,10);
    const rec = map.get(key) || { views: 0, clicks: 0 };
    labels.push(d.toLocaleDateString(undefined, { weekday: 'short' }));
    viewsData.push(rec.views);
    clicksData.push(rec.clicks);
  }

  const totalViews = viewsData.reduce((a,b)=>a+b,0);
  const totalClicks = clicksData.reduce((a,b)=>a+b,0);
  const vTot = document.getElementById('viewsTotal'); if (vTot) vTot.textContent = totalViews;
  const cTot = document.getElementById('clicksTotal'); if (cTot) cTot.textContent = totalClicks;
  if ((totalViews + totalClicks) === 0) { const empty = document.getElementById('chartEmpty'); if (empty) empty.classList.remove('hidden'); }

  // Load Chart.js (only on this page)
  const loadScript = (src) => new Promise((res, rej) => { const s=document.createElement('script'); s.src=src; s.onload=res; s.onerror=rej; document.head.appendChild(s); });
  loadScript('https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js').then(()=>{
    const ctx = document.getElementById('chart').getContext('2d');
    const g1 = ctx.createLinearGradient(0,0,0,260); g1.addColorStop(0,'rgba(37,99,235,.35)'); g1.addColorStop(1,'rgba(37,99,235,0)');
    const g2 = ctx.createLinearGradient(0,0,0,260); g2.addColorStop(0,'rgba(22,163,74,.35)'); g2.addColorStop(1,'rgba(22,163,74,0)');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels,
        datasets: [
          { label: 'Views', data: viewsData, borderColor: '#2563eb', backgroundColor: g1, tension: .35, fill: true, pointRadius: 0, borderWidth: 2 },
          { label: 'Clicks', data: clicksData, borderColor: '#16a34a', backgroundColor: g2, tension: .35, fill: true, pointRadius: 0, borderWidth: 2 }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        scales: {
          x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
          y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,.14)' }, ticks: { color: '#94a3b8', precision: 0 } }
        },
        plugins: {
          legend: { labels: { color: '#cbd5e1' } },
          tooltip: { backgroundColor: 'rgba(15,23,42,.95)', borderColor: 'rgba(148,163,184,.24)', borderWidth: 1, titleColor:'#e5e7eb', bodyColor:'#e5e7eb' }
        }
      }
    });
  }).catch(()=>{});
</script>
