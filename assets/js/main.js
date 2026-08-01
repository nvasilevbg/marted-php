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
var show = (f === 'Ð’ÑÐ¸Ñ‡ÐºÐ¸' || c.getAttribute('data-cat') === f);
c.style.display = show ? '' : 'none';
});
});
});
})();