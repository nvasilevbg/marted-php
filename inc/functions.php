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
    try {
        $st = db()->prepare("INSERT INTO bookings (bdate,slot,name,phone,service,notes,status) VALUES (?,?,?,?,?,?,'pending')");
        $st->execute([$d['date'],$d['slot'],trim($d['name']),trim($d['phone']),$d['service']??'',$d['notes']??'']);
        notify_booking($d);
        return ['ok'=>true];
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) return ['ok'=>false,'error'=>'Този час вече е зает. Моля, изберете друг.'];
        throw $e;
    }
}


function notify_booking($d) {
    $s = settings();
    $to = $s['email'] ?? '';
    if (!$to) return;
    $date = $d['date'];
    $slot = $d['slot'];
    $name = trim($d['name']);
    $phone = trim($d['phone']);
    $service = $d['service'] ?? '';
    $notes = $d['notes'] ?? '';
    
    // Google Calendar link
    $start = str_replace('-', '', $date) . 'T' . str_replace(':', '', $slot) . '00';
    $endHour = (int)substr($slot, 0, 2) + 1;
    $end = str_replace('-', '', $date) . 'T' . sprintf('%02d', $endHour) . '0000';
    $gcalText = urlencode("Монтаж - $name");
    $gcalDetails = urlencode("Име: $name
Телефон: $phone
Услуга: $service" . ($notes ? "
Бележка: $notes" : ''));
    $gcalLoc = urlencode($s['location'] ?? 'Добрич');
    $gcalUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=$gcalText&dates=$start/$end&details=$gcalDetails&location=$gcalLoc";
    
    $subject = "Нова резервация - $date $slot";
    $body = "Нова резервация от $name

";
    $body .= "Дата: $date
";
    $body .= "Час: $slot
";
    $body .= "Телефон: $phone
";
    $body .= "Услуга: $service
";
    if ($notes) $body .= "Бележка: $notes
";
    $body .= "
Добави в Google Calendar:
$gcalUrl
";
    $body .= "
Админ: " . ($s['base_url'] ?? '') . "/admin/bookings.php";
    
    $headers = "From: MarTed <noreply@dobrichmontaj.bg>
";
    $headers .= "Content-Type: text/plain; charset=utf-8
";
    $headers .= "Content-Transfer-Encoding: 8bit
";
    
    @mail($to, $subject, $body, $headers);
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