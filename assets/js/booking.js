(function(){
  var root = document.getElementById('booking');
  if (!root) return;
  var SLOTS = []; for (var i=8;i<=19;i++){ SLOTS.push((i<10?'0':'')+i+':00'); }
  var WEEK = ['Пн','Вт','Ср','Чт','Пт','Сб','Нд'];
  var MONTHS = ['Януари','Февруари','Март','Април','Май','Юни','Юли','Август','Септември','Октомври','Ноември','Декември'];
  var MONTHS2 = ['януари','февруари','март','април','май','юни','юли','август','септември','октомври','ноември','декември'];
  var WD = ['неделя','понеделник','вторник','сряда','четвъртък','петък','събота'];
  var taken = [];
  var step = 1, view = new Date(), selected = null, slot = null;
  var form = { name:'', phone:'', email:'', service:'', notes:'' };
  fetch('/api/taken.php').then(function(r){return r.json();}).then(function(d){ taken = d.taken || []; render(); }).catch(function(){ taken=[]; render(); });

  function pad(n){ return (n<10?'0':'')+n; }
  function fmt(d){ return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate()); }
  function today(){ return fmt(new Date()); }
  function nice(date){
    var p = date.split('-').map(Number); var d = new Date(p[0], p[1]-1, p[2]);
    return WD[d.getDay()] + ', ' + p[2] + ' ' + MONTHS2[p[1]-1] + ' ' + p[0];
  }
  function grid(){
    var y=view.getFullYear(), m=view.getMonth();
    var first=new Date(y,m,1); var lead=(first.getDay()+6)%7; var days=new Date(y,m+1,0).getDate();
    var cells=[]; for (var i=0;i<lead;i++) cells.push(null); for (var d=1;d<=days;d++) cells.push(fmt(new Date(y,m,d))); return cells;
  }
  function takenForDay(){ return taken.filter(function(t){return t.date===selected;}).map(function(t){return t.slot;}); }

  function el(html){ var d=document.createElement('div'); d.innerHTML=html; return d.firstElementChild; }

  function render(){
    root.innerHTML='';
    // steps indicator
    var ol = el('<ol class="wizardSteps" aria-label="Стъпки"></ol>');
    ['Дата','Час','Данни'].forEach(function(label, i){
      var n=i+1, active=(step===n), done=(step>n||step===4);
      var li=el('<li class="wStep '+(active?'active':'')+' '+(done?'done':'')+'"><span class="wStepNum">'+(done&&step!==n?'✓':n)+'</span><span class="wStepLabel">'+label+'</span>'+(i<2?'<span class="wStepBar"></span>':'')+'</li>');
      ol.appendChild(li);
    });
    root.appendChild(ol);
    var body=el('<div class="wizardBody"></div>');
    if (step===1) body.appendChild(renderCal());
    else if (step===2) body.appendChild(renderSlots());
    else if (step===3) body.appendChild(renderForm());
    else if (step===4) body.appendChild(renderDone());
    root.appendChild(body);
    if (step<4){
      var nav=el('<div class="wizardNav">'+(step>1?'<button class="btn btn-night" id="bkBack">Назад</button>':'<span></span>')+'<button class="btn btn-primary" id="bkNext">'+(step===3?'Запази часа':'Напред')+'</button></div>');
      root.appendChild(nav);
      if (step>1) document.getElementById('bkBack').onclick=function(){ step--; render(); };
      document.getElementById('bkNext').onclick=function(){
        if (step===3){ submit(); return; }
        if (step===1 && !selected) return;
        if (step===2 && !slot) return;
        step++; render();
      };
    }
  }
  function renderCal(){
    var d=el('<div class="bookingCal"></div>');
    var head=el('<div class="calHead"><button class="calNav" id="bkPrev">‹</button><span class="calMonth">'+MONTHS[view.getMonth()]+' '+view.getFullYear()+'</span><button class="calNav" id="bkNext2">›</button></div>');
    d.appendChild(head);
    var wk=el('<div class="calWeek">'+WEEK.map(function(w){return '<span>'+w+'</span>';}).join('')+'</div>'); d.appendChild(wk);
    var g=el('<div class="calGrid"></div>');
    grid().forEach(function(date){
      if (!date){ g.appendChild(el('<span class="calEmpty"></span>')); return; }
      var past=date<today(); var sel=date===selected; var has=taken.some(function(t){return t.date===date;});
      var b=el('<button class="calDay '+(sel?'sel':'')+' '+(past?'disabled':'')+'" '+(past?'disabled':'')+'>'+Number(date.slice(-2))+(has&&!past?'<i class="calDot"></i>':'')+'</button>');
      b.onclick=function(){ selected=date; slot=null; render(); };
      g.appendChild(b);
    });
    d.appendChild(g);
    var hint=el('<p class="calHint">Работно време: '+window.SITE_HOURS+'.</p>'); d.appendChild(hint);
    setTimeout(function(){
      var p=document.getElementById('bkPrev'), n=document.getElementById('bkNext2');
      if(p) p.onclick=function(){ view=new Date(view.getFullYear(), view.getMonth()-1, 1); render(); };
      if(n) n.onclick=function(){ view=new Date(view.getFullYear(), view.getMonth()+1, 1); render(); };
    },0);
    return d;
  }
  function renderSlots(){
    var d=el('<div class="wizardStep2"></div>');
    d.appendChild(el('<button class="linkBtn" id="bkBack2">‹ Смяна на датата</button>'));
    d.appendChild(el('<h3>'+(selected?nice(selected):'')+'</h3>'));
    var sg=el('<div class="slots"></div>');
    var tf=takenForDay();
    SLOTS.forEach(function(s){
      var takenS=tf.indexOf(s)>=0;
      var b=el('<button class="slot '+(slot===s?'sel':'')+' '+(takenS?'taken':'')+'" '+(takenS?'disabled':'')+'>'+s+'</button>');
      if(!takenS) b.onclick=function(){ slot=s; render(); };
      sg.appendChild(b);
    });
    d.appendChild(sg);
    d.appendChild(el('<p class="calHint">Заетите часове са зачеркнати. Изберете свободен час.</p>'));
    setTimeout(function(){ var b=document.getElementById('bkBack2'); if(b) b.onclick=function(){ step=1; render(); }; },0);
    return d;
  }
  function renderForm(){
    var d=el('<div class="wizardStep3"></div>');
    d.appendChild(el('<button class="linkBtn" id="bkBack3">‹ Смяна на часа</button>'));
    d.appendChild(el('<div class="wizardSummary"><span class="wSumTag">'+(selected?nice(selected):'')+'</span><span class="wSumTag">'+(slot||'')+'</span></div>'));
    var opt = window.SERVICES.map(function(s){return '<option value="'+s+'"'+(s===form.service?' selected':'')+'>'+s+'</option>';}).join('');
    d.appendChild(el('<div class="field"><label for="bk-name">Име *</label><input id="bk-name" value="'+form.name+'" placeholder="Вашето име"></div>'));
    d.appendChild(el('<div class="field"><label for="bk-phone">Телефон *</label><input id="bk-phone" value="'+form.phone+'" placeholder="0888 123 456"></div>'));
    d.appendChild(el('<div class="field"><label for="bk-email">Имейл</label><input id="bk-email" type="email" value="'+form.email+'" placeholder="vashe@imayl.bg"></div>'));
    d.appendChild(el('<div class="field"><label for="bk-service">Услуга</label><select id="bk-service">'+opt+'</select></div>'));
    d.appendChild(el('<div class="field"><label for="bk-notes">Бележка (по желание)</label><textarea id="bk-notes" rows="3" placeholder="Адрес, етаж, детайли за монтажа…">'+form.notes+'</textarea></div>'));
    d.appendChild(el('<p id="bkMsg" class="formMsg err" style="display:none"></p>'));
    d.appendChild(el('<p class="formNote">Потвърждаваме по телефон. Можете да се обадите директно: '+window.SITE_PHONE+'.</p>'));
    setTimeout(function(){
      document.getElementById('bkBack3').onclick=function(){ step=2; render(); };
      ['name','phone','email','service','notes'].forEach(function(k){
        var f=document.getElementById('bk-'+k); if(f) f.oninput=function(){ form[k]=f.value; };
        if(f && f.tagName==='SELECT') f.onchange=function(){ form[k]=f.value; };
      });
    },0);
    return d;
  }
  function renderDone(){
    var d=el('<div class="wizardDone"><div class="wizardDoneIcon">✓</div><h3>Часът е запазен!</h3><p class="wizardDoneInfo">'+(selected?nice(selected):'')+' от '+slot+'</p><p class="wizardDoneSub">Ще се свържем с вас на посочения телефон за потвърждение. Благодарим ви!</p><button class="btn btn-ghost" id="bkReset">Запази нов час</button></div>');
    setTimeout(function(){ var r=document.getElementById('bkReset'); if(r) r.onclick=function(){ selected=null; slot=null; form={name:'',phone:'',email:'',service:window.SERVICES[0]||'',notes:''}; step=1; render(); }; },0);
    return d;
  }
  function submit(){
    if (!selected || !slot){ showErr('Моля, изберете дата и час.'); return; }
    if (!form.name.trim() || !form.phone.trim()){ showErr('Моля, попълнете име и телефон.'); return; }
    var btn=document.getElementById('bkNext'); if(btn) btn.disabled=true; if(btn) btn.textContent='Запазване…';
    fetch('/api/book.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({date:selected,slot:slot,name:form.name,phone:form.phone,email:form.email,service:form.service,notes:form.notes})})
      .then(function(r){return r.json();})
      .then(function(d){ if(d.ok){ taken.push({date:selected,slot:slot}); step=4; render(); } else { showErr(d.error||'Грешка при запазване.'); if(btn){btn.disabled=false;btn.textContent='Запази часа';} } })
      .catch(function(){ showErr('Няма връзка със сървъра.'); if(btn){btn.disabled=false;btn.textContent='Запази часа';} });
  }
  function showErr(msg){ var m=document.getElementById('bkMsg'); if(m){ m.textContent=msg; m.style.display='block'; } }
  window.SERVICES = window.SERVICES || ['Монтаж на мебели'];
  render();
})();