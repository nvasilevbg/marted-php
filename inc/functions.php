<?php
require_once __DIR__ . '/db.php';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function settings() {
    $rows = db()->query("SELECT k,v FROM settings")->fetchAll();
    $s = [];
    foreach ($rows as $r) $s[$r['k']] = $r['v'];
    $s += ['name'=>'MarTed','subtitle'=>'монтаж на мебели','tagline'=>'Фирма за монтаж и демонтаж','phone'=>'','phoneHref'=>'#','email'=>'','location'=>'','region'=>'','hours'=>'','established'=>''];
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

// --- DB-backed content ---
function services() {
    $rows = db()->query("SELECT * FROM services ORDER BY sort_order ASC, id ASC")->fetchAll();
    return array_map(function($r) { return ['title'=>$r['title'],'text'=>$r['stext'],'icon'=>$r['icon'],'image'=>$r['image']]; }, $rows);
}
function stats() {
    $rows = db()->query("SELECT * FROM stats ORDER BY sort_order ASC, id ASC")->fetchAll();
    return array_map(function($r) { return ['value'=>$r['svalue'],'label'=>$r['slabel']]; }, $rows);
}
function testimonials() {
    $rows = db()->query("SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC")->fetchAll();
    return array_map(function($r) { return ['name'=>$r['tname'],'text'=>$r['ttext'],'stars'=>$r['stars']]; }, $rows);
}
function content($key, $def='') {
    $st = db()->prepare("SELECT v FROM settings WHERE k=?");
    $st->execute([$key]);
    $v = $st->fetchColumn();
    return ($v !== false && $v !== '') ? $v : $def;
}
function filters() { return ['Всички','Кухни','Спални','Гардероби','Други']; }