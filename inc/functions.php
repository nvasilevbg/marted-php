<?php
require_once __DIR__ . '/db.php';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function settings() {
    $rows = db()->query("SELECT k,v FROM settings")->fetchAll();
    $s = [];
    foreach ($rows as $r) $s[$r['k']] = $r['v'];
    $s += ['name'=>'MarTed','subtitle'=>'монтаж на мебели','tagline'=>'Фирма за монтаж и демонтаж','phone'=>'','phoneHref'=>'#','email'=>'','location'=>'','region'=>'','hours'=>'','established'=>'','facebook'=>'','instagram'=>''];
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
        $st = db()->prepare("INSERT INTO bookings (bdate,slot,name,phone,email,service,notes,status) VALUES (?,?,?,?,?,?,?,'pending')");
        $st->execute([$d['date'],$d['slot'],trim($d['name']),trim($d['phone']),trim($d['email']??''),$d['service']??'',$d['notes']??'']);
        notify_booking($d);
        return ['ok'=>true];
    } catch (\PDOException $e) {
        if ($e->getCode() == 23000) return ['ok'=>false,'error'=>'Този час вече е зает. Моля, изберете друг.'];
        throw $e;
    }
}


function notify_booking($d) {
    $s = settings();
    $ownerEmail = $s['email'] ?? '';
    $customerEmail = trim($d['email'] ?? '');
    $date = $d['date'];
    $slot = $d['slot'];
    $name = trim($d['name']);
    $phone = trim($d['phone']);
    $service = $d['service'] ?? '';
    $notes = $d['notes'] ?? '';
    
    // Google Calendar event (direct API)
    $gcalResult = create_calendar_event($d, $s);
    
    $subject = "Нова резервация - $date $slot";
    $headers = "From: MarTed <noreply@dobrichmontaj.bg>\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";
    
    // Email to site owner
    if ($ownerEmail) {
        $ownerBody = "Нова резервация от $name\n\n";
        $ownerBody .= "Дата: $date\nЧас: $slot\n";
        $ownerBody .= "Име: $name\nТелефон: $phone\n";
        if ($customerEmail) $ownerBody .= "Имейл: $customerEmail\n";
        $ownerBody .= "Услуга: $service\n";
        if ($notes) $ownerBody .= "Бележка: $notes\n";
        $ownerBody .= "\nАдмин: " . ($s['base_url'] ?? '') . "/admin/bookings.php";
        if ($gcalResult) $ownerBody .= "\nДобавено в Google Calendar";
        @mail($ownerEmail, $subject, $ownerBody, $headers);
    }
    
    // Email to customer
    if ($customerEmail) {
        $custSubject = "Запазен час - MarTed";
        $custBody = "Здравейте, $name!\n\n";
        $custBody .= "Запазихте час за $date от $slot.\n";
        $custBody .= "Ще се свържем с вас за потвърждение.\n\n";
        $custBody .= "Телефон: " . ($s['phone'] ?? '') . "\n";
        $custBody .= "MarTed - монтаж и демонтаж на мебели";
        @mail($customerEmail, $custSubject, $custBody, $headers);
    }
}

function create_calendar_event($d, $s) {
    $keyFile = __DIR__ . '/../google-service-account.json';
    if (!file_exists($keyFile)) return false;
    $keyData = json_decode(file_get_contents($keyFile), true);
    if (!$keyData || empty($keyData['private_key'])) return false;
    
    $now = time();
    $header = ['alg' => 'RS256', 'typ' => 'JWT'];
    $payload = [
        'iss' => $keyData['client_email'],
        'scope' => 'https://www.googleapis.com/auth/calendar',
        'aud' => 'https://oauth2.googleapis.com/token',
        'exp' => $now + 3600,
        'iat' => $now
    ];
    
    $b64 = function($data) { return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); };
    $headerB64 = $b64(json_encode($header));
    $payloadB64 = $b64(json_encode($payload));
    $signInput = $headerB64 . '.' . $payloadB64;
    
    openssl_sign($signInput, $signature, $keyData['private_key'], 'SHA256');
    $jwt = $signInput . '.' . $b64($signature);
    
    // Get access token
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(['grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer', 'assertion' => $jwt]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10
    ]);
    $tokenResp = json_decode(curl_exec($ch), true);
    curl_close($ch);
    if (empty($tokenResp['access_token'])) return false;
    
    // Create event
    $startHour = (int)substr($d['slot'], 0, 2);
    $startDT = $d['date'] . 'T' . sprintf('%02d:00:00', $startHour);
    $endDT = $d['date'] . 'T' . sprintf('%02d:00:00', $startHour + 1);
    
    $summary = "Монтаж - " . trim($d['name']);
    $desc = "Телефон: " . trim($d['phone']) . "\nУслуга: " . ($d['service'] ?? '');
    if (!empty($d['notes'])) $desc .= "\nБележка: " . $d['notes'];
    
    $event = [
        'summary' => $summary,
        'description' => $desc,
        'start' => ['dateTime' => $startDT, 'timeZone' => 'Europe/Sofia'],
        'end' => ['dateTime' => $endDT, 'timeZone' => 'Europe/Sofia'],
        'location' => $s['location'] ?? ''
    ];
    
    $calendarId = urlencode($keyData['calendar_id'] ?? 'primary');
    $ch = curl_init("https://www.googleapis.com/calendar/v3/calendars/$calendarId/events?sendNotifications=true");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($event),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $tokenResp['access_token'],
            'Content-Type: application/json'
        ]
    ]);
    $eventResp = json_decode(curl_exec($ch), true);
    curl_close($ch);
    return !empty($eventResp['id']);
}


function srcset($path) {
    $info = pathinfo($path);
    $base = $info['dirname'] . '/' . $info['filename'];
    $ext = $info['extension'] ?? 'webp';
    $sets = [];
    $w400 = $base . '-400.' . $ext;
    if (file_exists(__DIR__ . '/..' . $w400)) $sets[] = $w400 . ' 400w';
    $w800 = $base . '-800.' . $ext;
    if (file_exists(__DIR__ . '/..' . $w800)) $sets[] = $w800 . ' 800w';
    $sets[] = $path . ' 1200w';
    return implode(', ', $sets);
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
function content_with_html($key, $def='') {
    $st = db()->prepare("SELECT v FROM settings WHERE k=?");
    $st->execute([$key]);
    $v = $st->fetchColumn();
    return ($v !== false && $v !== '') ? $v : $def;
}