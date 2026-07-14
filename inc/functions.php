<?php
require_once __DIR__ . '/db.php';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function settings() {
    static $s = null;
    if ($s === null) {
        $rows = db()->query("SELECT k,v FROM settings")->fetchAll();
        $s = [];
        foreach ($rows as $r) $s[$r['k']] = $r['v'];
        $s += ['name'=>'MarTed','subtitle'=>'монтаж на мебели','tagline'=>'Фирма за монтаж и демонтаж','phone'=>'','phoneHref'=>'#','email'=>'','location'=>'','region'=>'','hours'=>'','established'=>''];
    }
    return $s;
}
function setting($k, $def='') { $s = settings(); return isset($s[$k]) && $s[$k] !== '' ? $s[$k] : $def; }

function projects() {
    $rows = db()->query("SELECT * FROM projects ORDER BY id ASC")->fetchAll();
    foreach ($rows as &$p) $p['gallery'] = json_decode($p['gallery'] ?: '[]', true) ?: [];
    unset($p);
    return $rows;
}
function project_by_slug($slug) {
    $st = db()->prepare("SELECT * FROM projects WHERE slug=?");
    $st->execute([$slug]);
    $p = $st->fetch();
    if ($p) $p['gallery'] = json_decode($p['gallery'] ?: '[]', true) ?: [];
    return $p ?: null;
}
function taken_slots() {
    $rows = db()->query("SELECT bdate, slot FROM bookings WHERE status<>'cancelled'")->fetchAll();
    return array_map(fn($r)=>['date'=>$r['bdate'],'slot'=>$r['slot']], $rows);
}

function add_booking($d) {
    $today = date('Y-m-d');
    if (empty($d['date']) || empty($d['slot']) || empty(trim($d['name'])) || empty(trim($d['phone']))) return ['ok'=>false,'error'=>'Моля, попълнете име и телефон.'];
    if ($d['date'] < $today) return ['ok'=>false,'error'=>'Избрали сте дата в миналото.'];
    $chk = db()->prepare("SELECT 1 FROM bookings WHERE status<>'cancelled' AND bdate=? AND slot=?");
    $chk->execute([$d['date'],$d['slot']]);
    if ($chk->fetch()) return ['ok'=>false,'error'=>'Този час вече е зает. Моля, изберете друг.'];
    $st = db()->prepare("INSERT INTO bookings (bdate,slot,name,phone,service,notes,status) VALUES (?,?,?,?,?,?,'pending')");
    $st->execute([$d['date'],$d['slot'],trim($d['name']),trim($d['phone']),$d['service']??'',$d['notes']??'']);
    return ['ok'=>true];
}

// --- static content ---
function services() {
    return [
        ['title'=>'Монтаж на мебели','text'=>'Монтаж на кухни, спални, гардероби, секции и всякакви мебели.','icon'=>'drill'],
        ['title'=>'Демонтаж на мебели','text'=>'Професионален демонтаж и опаковане при пренасяне или ремонт.','icon'=>'demolition'],
        ['title'=>'Разнос на мебели','text'=>'Транспорт и разнос на мебели до адрес и етаж по ваш избор.','icon'=>'truck'],
        ['title'=>'Изнасяне на стари мебели','text'=>'Изнасяме и извозваме стари мебели и ненужни вещи.','icon'=>'box'],
        ['title'=>'Замерване и консултация','text'=>'Замерване на място и консултация за вашия проект.','icon'=>'measure'],
        ['title'=>'Коректност и гаранция','text'=>'Работим чисто и прецизно с гаранция за качество.','icon'=>'shield'],
    ];
}
function stats() {
    return [['value'=>'500+','label'=>'Доволни клиенти'],['value'=>'1200+','label'=>'Монтирани мебели'],['value'=>'5+','label'=>'Години опит'],['value'=>'Добрич','label'=>'и околността']];
}
function testimonials() {
    return [
        ['name'=>'Иван Петров','text'=>'Много съм доволен от услугата. Бързи, точни и коректни. Препоръчвам.'],
        ['name'=>'Мария Георгиева','text'=>'Стегнат екип. Монтираха ми кухнята чисто и прецизно. Благодаря.'],
        ['name'=>'Георги Йорданов','text'=>'Професионалисти. Работят точно и подредено. Много съм доволен.'],
    ];
}
function filters() { return ['Всички','Кухни','Спални','Гардероби','Други']; }