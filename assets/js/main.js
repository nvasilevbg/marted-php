
(function(){
var els = document.querySelectorAll('.reveal');
if (!els.length || !('IntersectionObserver' in window)) { els.forEach(function(e){e.classList.add('is-visible')}); return; }
var io = new IntersectionObserver(function(entries){
entries.forEach(function(en){ if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); } });
}, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
els.forEach(function(e){ io.observe(e); });
})();
(function(){
var tabs = document.querySelectorAll('.tabs button[data-filter]');
if (!tabs.length) return;
var cards = document.querySelectorAll('.projectCard');
tabs.forEach(function(t){
t.addEventListener('click', function(){
tabs.forEach(function(x){ x.classList.remove('active'); });
t.classList.add('active');
var f = t.getAttribute('data-filter');
cards.forEach(function(c){
var show = (f === 'Всички' || c.getAttribute('data-cat') === f);
c.style.display = show ? '' : 'none';
});
});
});
})();