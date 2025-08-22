(function(){
  window.showToast = function(msg){
    const el = document.getElementById('toast'); if(!el) return;
    el.textContent = msg; el.classList.remove('hidden'); el.style.opacity = '1';
    setTimeout(()=>{ el.style.transition='opacity .4s'; el.style.opacity='0'; setTimeout(()=>{ el.classList.add('hidden'); el.style.transition=''; }, 400); }, 1200);
  }
})();
