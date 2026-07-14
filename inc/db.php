<?php
// PDO connection + avto-instalaciq za sqlite (lokalen test). Za mysql se bijaga schema.sql.
function config() { static $c; if ($c === null) $c = require __DIR__ . '/config.php'; return $c; }

function db() {
    static $pdo = null;
    if ($pdo !== null) return $pdo;
    $cfg = config()['db'];
    if ($cfg['driver'] === 'sqlite') {
        $path = $cfg['sqlite_path'];
        $dir = dirname($path);
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        $pdo = new PDO('sqlite:' . $path);
        $pdo->exec('PRAGMA journal_mode=WAL; PRAGMA foreign_keys=ON;');
    } else {
        $m = $cfg['mysql'];
        $dsn = "mysql:host={$m['host']};dbname={$m['name']};charset={$m['charset']}";
        $pdo = new PDO($dsn, $m['user'], $m['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    db_install($pdo);
    return $pdo;
}

function db_install($pdo) {
    $exists = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='settings'")->fetch();
    if ($exists) return;
    $pdo->exec("CREATE TABLE settings (k TEXT PRIMARY KEY, v TEXT)");
    $pdo->exec("CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, title TEXT, category TEXT, pdate TEXT, location TEXT, description TEXT, cover TEXT, gallery TEXT, created_at TEXT DEFAULT (datetime('now')))");
    $pdo->exec("CREATE TABLE bookings (id INTEGER PRIMARY KEY AUTOINCREMENT, bdate TEXT, slot TEXT, name TEXT, phone TEXT, service TEXT, notes TEXT, status TEXT DEFAULT 'pending', created_at TEXT DEFAULT (datetime('now')))");
    db_seed($pdo);
}

function db_seed($pdo) {
    $s = [
        'name'=>'MarTed','subtitle'=>'монтаж на мебели','tagline'=>'Фирма за монтаж и демонтаж',
        'phone'=>'0898 535 885','phoneHref'=>'tel:+359898535885','email'=>'marted.montaj@gmail.com',
        'location'=>'гр. Добрич и околността','region'=>'Работим в Добрич, Балчик, Варна и околността',
        'hours'=>'Пон – Нед: 08:00 – 20:00','established'=>'2019',
    ];
    $st = $pdo->prepare("INSERT INTO settings (k,v) VALUES (?,?)");
    foreach ($s as $k=>$v) $st->execute([$k,$v]);
    $seed = [
        ['kuhnya-po-porachka','Кухня по поръчка','Кухни','Юли 2024','Добрич','Монтаж на кухня по поръчка с остров, вградени шкафове и завършващи панели.','/assets/media/kitchen-2.jpg',['/assets/media/kitchen-2.jpg','/assets/media/kitchen-1.jpg','/assets/media/kitchen-3.jpg','/assets/media/kitchen-4.jpg']],
        ['moderna-kuhnya','Модерна кухня','Кухни','Юни 2024','Балчик','Монтаж на модерна кухня с остров, вградени шкафове и завършващи панели.','/assets/media/kitchen-3.jpg',['/assets/media/kitchen-3.jpg','/assets/media/kitchen-4.jpg','/assets/media/kitchen-1.jpg']],
        ['spalnya-i-garderob','Спалня и гардероб','Спални','Април 2024','Добрич','Сглобяване на спалня, нощни шкафчета и гардероб с плъзгащи се врати.','/assets/media/bedroom-1.jpg',['/assets/media/bedroom-1.jpg','/assets/media/bedroom-2.jpg','/assets/media/living-3.jpg']],
        ['garderob-po-razmer','Гардероб по размер','Гардероби','Март 2024','Варна','Монтаж на висок гардероб по размер с плавно затваряне и вътрешно разпределение.','/assets/media/bedroom-2.jpg',['/assets/media/bedroom-2.jpg','/assets/media/bedroom-1.jpg','/assets/media/kitchen-4.jpg']],
        ['sektsiya-za-dnevna','Секция за дневна','Други','Февруари 2024','Добрич','Монтаж на секция за дневна с ТВ панел, шкафове и скрит монтаж.','/assets/media/living-1.jpg',['/assets/media/living-1.jpg','/assets/media/living-2.jpg','/assets/media/living-3.jpg']],
        ['ofis-obzavezhdane','Офис обзавеждане','Други','Януари 2024','Добрич','Монтаж на офис бюро, шкафове, етажерки и конферентна маса.','/assets/media/living-3.jpg',['/assets/media/living-3.jpg','/assets/media/living-2.jpg','/assets/media/kitchen-4.jpg']],
    ];
    $st = $pdo->prepare("INSERT INTO projects (slug,title,category,pdate,location,description,cover,gallery) VALUES (?,?,?,?,?,?,?,?)");
    foreach ($seed as $p) $st->execute([$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6],json_encode($p[7])]);
}