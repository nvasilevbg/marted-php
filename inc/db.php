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
        db_migrate_mysql($pdo);
        return $pdo;
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    db_install($pdo);
    return $pdo;
}


function db_migrate_mysql($pdo) {
    $exists = $pdo->query("SHOW TABLES LIKE 'settings'")->fetch();
    if ($exists) return;
    $sql = @file_get_contents(__DIR__ . '/../schema.sql');
    if ($sql === false) return;
    foreach (preg_split('/;[\r\n]+/', $sql) as $stmt) {
        $stmt = trim($stmt);
        if ($stmt !== '') $pdo->exec($stmt);
    }
}
function db_install($pdo) {
    $exists = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='settings'")->fetch();
    if ($exists) return;
    $pdo->exec("CREATE TABLE settings (k TEXT PRIMARY KEY, v TEXT)");
    $pdo->exec("CREATE TABLE projects (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, title TEXT, category TEXT, pdate TEXT, location TEXT, description TEXT, cover TEXT, gallery TEXT, created_at TEXT DEFAULT (datetime('now')))");
    $pdo->exec("CREATE TABLE bookings (id INTEGER PRIMARY KEY AUTOINCREMENT, bdate TEXT, slot TEXT, name TEXT, phone TEXT, service TEXT, notes TEXT, status TEXT DEFAULT 'pending', created_at TEXT DEFAULT (datetime('now')))");
    $pdo->exec("CREATE TABLE services (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, stext TEXT, icon TEXT, image TEXT, sort_order INTEGER DEFAULT 0)");
    $pdo->exec("CREATE TABLE testimonials (id INTEGER PRIMARY KEY AUTOINCREMENT, tname TEXT, ttext TEXT, stars INTEGER DEFAULT 5, sort_order INTEGER DEFAULT 0)");
    $pdo->exec("CREATE TABLE stats (id INTEGER PRIMARY KEY AUTOINCREMENT, svalue TEXT, slabel TEXT, sort_order INTEGER DEFAULT 0)");
    db_seed($pdo);
}

function db_seed($pdo) {
    $s = [
        'name'=>'MarTed','subtitle'=>'Ð¼Ð¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸','tagline'=>'Ð¤Ð¸Ñâ‚¬Ð¼Ð° Ð·Ð° Ð¼Ð¾Ð½Ñâ€šÐ°Ð¶ Ð¸ Ð´ÐµÐ¼Ð¾Ð½Ñâ€šÐ°Ð¶',
        'phone'=>'0898 535 885','phoneHref'=>'tel:+359898535885','email'=>'marted.montaj@gmail.com',
        'location'=>'Ð³Ñâ‚¬. Ðâ€Ð¾Ð±Ñâ‚¬Ð¸Ñâ€¡ Ð¸ Ð¾ÐºÐ¾Ð»Ð½Ð¾ÑÑâ€šÑâ€šÐ°','region'=>'Ð Ð°Ð±Ð¾Ñâ€šÐ¸Ð¼ Ð² Ðâ€Ð¾Ð±Ñâ‚¬Ð¸Ñâ€¡, Ðâ€˜Ð°Ð»Ñâ€¡Ð¸Ðº, Ðâ€™Ð°Ñâ‚¬Ð½Ð° Ð¸ Ð¾ÐºÐ¾Ð»Ð½Ð¾ÑÑâ€šÑâ€šÐ°',
        'hours'=>'ÐŸÐ¾Ð½ ââ‚¬â€œ ÐÐµÐ´: 08:00 ââ‚¬â€œ 20:00','established'=>'2019',
    ];
    $st = $pdo->prepare("INSERT INTO settings (k,v) VALUES (?,?)");
    foreach ($s as $k=>$v) $st->execute([$k,$v]);
    // page content
    $content = [
        'home_hero_title'=>'ÐœÐ¾Ð½Ñâ€šÐ°Ð¶ Ð¸ Ð´ÐµÐ¼Ð¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸',
        'home_hero_lead'=>'ÐŸÑâ‚¬Ð¾Ñâ€žÐµÑÐ¸Ð¾Ð½Ð°Ð»ÐµÐ½ Ð¼Ð¾Ð½Ñâ€šÐ°Ð¶, Ð´ÐµÐ¼Ð¾Ð½Ñâ€šÐ°Ð¶, Ñâ‚¬Ð°Ð·Ð½Ð¾Ñ Ð¸ Ð¸Ð·Ð½Ð°ÑÑÐ½Ðµ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸ Ð² Ðâ€Ð¾Ð±Ñâ‚¬Ð¸Ñâ€¡, Ðâ€˜Ð°Ð»Ñâ€¡Ð¸Ðº, Ðâ€™Ð°Ñâ‚¬Ð½Ð° Ð¸ Ð¾ÐºÐ¾Ð»Ð½Ð¾ÑÑâ€šÑâ€šÐ° ââ‚¬â€ Ñ Ð³Ð°Ñâ‚¬Ð°Ð½Ñâ€ Ð¸Ñ Ð·Ð° ÐºÐ°Ñâ€¡ÐµÑÑâ€šÐ²Ð¾, Ñâ€šÐ¾Ñâ€¡Ð½Ð¾ÑÑâ€š Ð¸ Ñâ€¡Ð¸ÑÑâ€š Ð·Ð°Ð²ÑŠÑâ‚¬ÑˆÐµÐº.',
        'about_heading'=>'ÐŸÑŠÑâ‚¬Ð²Ð¾ ÑÐµ ÑƒÑâ€šÐ¾Ñâ€¡Ð½ÑÐ²Ð° Ð·Ð°Ð´Ð°Ñâ€¡Ð°Ñâ€šÐ°. Ð¡Ð»ÐµÐ´ Ñâ€šÐ¾Ð²Ð° ÑÐµ Ñâ‚¬Ð°Ð±Ð¾Ñâ€šÐ¸.',
        'home_hero_image'=>'/assets/media/hero-kitchen.jpg','about_text'=>'Ðâ€™ÑÐµÐºÐ¸ Ð¼Ð¾Ð½Ñâ€šÐ°Ð¶ Ð·Ð°Ð¿Ð¾Ñâ€¡Ð²Ð° Ñ Ð¿Ñâ‚¬Ð¾Ð²ÐµÑâ‚¬ÐºÐ° Ð½Ð° Ð¾Ð±ÐµÐºÑâ€šÐ°, Ñâ‚¬Ð°Ð·Ð¼ÐµÑâ‚¬Ð¸Ñâ€šÐµ Ð¸ Ð¾ÑÐ¾Ð±ÐµÐ½Ð¾ÑÑâ€šÐ¸Ñâ€šÐµ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸Ñâ€šÐµ. Ð¦ÐµÐ»Ñâ€šÐ° Ðµ Ð´Ð° Ð½ÑÐ¼Ð° Ð¸Ð·Ð½ÐµÐ½Ð°Ð´Ð¸, ÐºÑâ‚¬Ð¸Ð² Ð¼Ð¾Ð½Ñâ€šÐ°Ð¶, Ð»Ð¸Ð¿ÑÐ²Ð°Ñâ€°Ð¸ ÐµÐ»ÐµÐ¼ÐµÐ½Ñâ€šÐ¸ Ð¸Ð»Ð¸ Ð½ÐµÐ´Ð¾Ð²ÑŠÑâ‚¬ÑˆÐµÐ½Ð° Ñâ‚¬Ð°Ð±Ð¾Ñâ€šÐ°.',
    ];
    $st2 = $pdo->prepare("INSERT INTO settings (k,v) VALUES (?,?)");
    foreach ($content as $k=>$v) $st2->execute([$k,$v]);
    // services
    $svcs = [
        ['ÐœÐ¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸','ÐœÐ¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° ÐºÑƒÑâ€¦Ð½Ð¸, ÑÐ¿Ð°Ð»Ð½Ð¸, Ð³Ð°Ñâ‚¬Ð´ÐµÑâ‚¬Ð¾Ð±Ð¸, ÑÐµÐºÑâ€ Ð¸Ð¸ Ð¸ Ð²ÑÑÐºÐ°ÐºÐ²Ð¸ Ð¼ÐµÐ±ÐµÐ»Ð¸.','drill','/assets/media/kitchen-1.jpg',1],
        ['Ðâ€ÐµÐ¼Ð¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸','ÐŸÑâ‚¬Ð¾Ñâ€žÐµÑÐ¸Ð¾Ð½Ð°Ð»ÐµÐ½ Ð´ÐµÐ¼Ð¾Ð½Ñâ€šÐ°Ð¶ Ð¸ Ð¾Ð¿Ð°ÐºÐ¾Ð²Ð°Ð½Ðµ Ð¿Ñâ‚¬Ð¸ Ð¿Ñâ‚¬ÐµÐ½Ð°ÑÑÐ½Ðµ Ð¸Ð»Ð¸ Ñâ‚¬ÐµÐ¼Ð¾Ð½Ñâ€š.','demolition','',2],
        ['Ð Ð°Ð·Ð½Ð¾Ñ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸','Ð¢Ñâ‚¬Ð°Ð½ÑÐ¿Ð¾Ñâ‚¬Ñâ€š Ð¸ Ñâ‚¬Ð°Ð·Ð½Ð¾Ñ Ð½Ð° Ð¼ÐµÐ±ÐµÐ»Ð¸ Ð´Ð¾ Ð°Ð´Ñâ‚¬ÐµÑ Ð¸ ÐµÑâ€šÐ°Ð¶ Ð¿Ð¾ Ð²Ð°Ñˆ Ð¸Ð·Ð±Ð¾Ñâ‚¬.','truck','',3],
        ['Ð˜Ð·Ð½Ð°ÑÑÐ½Ðµ Ð½Ð° ÑÑâ€šÐ°Ñâ‚¬Ð¸ Ð¼ÐµÐ±ÐµÐ»Ð¸','Ð˜Ð·Ð½Ð°ÑÑÐ¼Ðµ Ð¸ Ð¸Ð·Ð²Ð¾Ð·Ð²Ð°Ð¼Ðµ ÑÑâ€šÐ°Ñâ‚¬Ð¸ Ð¼ÐµÐ±ÐµÐ»Ð¸ Ð¸ Ð½ÐµÐ½ÑƒÐ¶Ð½Ð¸ Ð²ÐµÑâ€°Ð¸.','box','',4],
        ['Ðâ€”Ð°Ð¼ÐµÑâ‚¬Ð²Ð°Ð½Ðµ Ð¸ ÐºÐ¾Ð½ÑÑƒÐ»Ñâ€šÐ°Ñâ€ Ð¸Ñ','Ðâ€”Ð°Ð¼ÐµÑâ‚¬Ð²Ð°Ð½Ðµ Ð½Ð° Ð¼ÑÑÑâ€šÐ¾ Ð¸ ÐºÐ¾Ð½ÑÑƒÐ»Ñâ€šÐ°Ñâ€ Ð¸Ñ Ð·Ð° Ð²Ð°ÑˆÐ¸Ñ Ð¿Ñâ‚¬Ð¾ÐµÐºÑâ€š.','measure','',5],
        ['ÐšÐ¾Ñâ‚¬ÐµÐºÑâ€šÐ½Ð¾ÑÑâ€š Ð¸ Ð³Ð°Ñâ‚¬Ð°Ð½Ñâ€ Ð¸Ñ','Ð Ð°Ð±Ð¾Ñâ€šÐ¸Ð¼ Ñâ€¡Ð¸ÑÑâ€šÐ¾ Ð¸ Ð¿Ñâ‚¬ÐµÑâ€ Ð¸Ð·Ð½Ð¾ Ñ Ð³Ð°Ñâ‚¬Ð°Ð½Ñâ€ Ð¸Ñ Ð·Ð° ÐºÐ°Ñâ€¡ÐµÑÑâ€šÐ²Ð¾.','shield','',6],
    ];
    $st3 = $pdo->prepare("INSERT INTO services (title,stext,icon,image,sort_order) VALUES (?,?,?,?,?)");
    foreach ($svcs as $s) $st3->execute($s);
    // testimonials
    $tests = [
        ['Ð˜Ð²Ð°Ð½ ÐŸÐµÑâ€šÑâ‚¬Ð¾Ð²','ÐœÐ½Ð¾Ð³Ð¾ ÑÑŠÐ¼ Ð´Ð¾Ð²Ð¾Ð»ÐµÐ½ Ð¾Ñâ€š ÑƒÑÐ»ÑƒÐ³Ð°Ñâ€šÐ°. Ðâ€˜ÑŠÑâ‚¬Ð·Ð¸, Ñâ€šÐ¾Ñâ€¡Ð½Ð¸ Ð¸ ÐºÐ¾Ñâ‚¬ÐµÐºÑâ€šÐ½Ð¸. ÐŸÑâ‚¬ÐµÐ¿Ð¾Ñâ‚¬ÑŠÑâ€¡Ð²Ð°Ð¼.',5,1],
        ['ÐœÐ°Ñâ‚¬Ð¸Ñ Ðâ€œÐµÐ¾Ñâ‚¬Ð³Ð¸ÐµÐ²Ð°','Ð¡Ñâ€šÐµÐ³Ð½Ð°Ñâ€š ÐµÐºÐ¸Ð¿. ÐœÐ¾Ð½Ñâ€šÐ¸Ñâ‚¬Ð°Ñâ€¦Ð° Ð¼Ð¸ ÐºÑƒÑâ€¦Ð½ÑÑâ€šÐ° Ñâ€¡Ð¸ÑÑâ€šÐ¾ Ð¸ Ð¿Ñâ‚¬ÐµÑâ€ Ð¸Ð·Ð½Ð¾. Ðâ€˜Ð»Ð°Ð³Ð¾Ð´Ð°Ñâ‚¬Ñ.',5,2],
        ['Ðâ€œÐµÐ¾Ñâ‚¬Ð³Ð¸ Ðâ„¢Ð¾Ñâ‚¬Ð´Ð°Ð½Ð¾Ð²','ÐŸÑâ‚¬Ð¾Ñâ€žÐµÑÐ¸Ð¾Ð½Ð°Ð»Ð¸ÑÑâ€šÐ¸. Ð Ð°Ð±Ð¾Ñâ€šÑÑâ€š Ñâ€šÐ¾Ñâ€¡Ð½Ð¾ Ð¸ Ð¿Ð¾Ð´Ñâ‚¬ÐµÐ´ÐµÐ½Ð¾. ÐœÐ½Ð¾Ð³Ð¾ ÑÑŠÐ¼ Ð´Ð¾Ð²Ð¾Ð»ÐµÐ½.',5,3],
    ];
    $st4 = $pdo->prepare("INSERT INTO testimonials (tname,ttext,stars,sort_order) VALUES (?,?,?,?)");
    foreach ($tests as $t) $st4->execute($t);
    // stats
    $stats = [
        ['500+','Ðâ€Ð¾Ð²Ð¾Ð»Ð½Ð¸ ÐºÐ»Ð¸ÐµÐ½Ñâ€šÐ¸',1],['1200+','ÐœÐ¾Ð½Ñâ€šÐ¸Ñâ‚¬Ð°Ð½Ð¸ Ð¼ÐµÐ±ÐµÐ»Ð¸',2],['5+','Ðâ€œÐ¾Ð´Ð¸Ð½Ð¸ Ð¾Ð¿Ð¸Ñâ€š',3],['Ðâ€Ð¾Ð±Ñâ‚¬Ð¸Ñâ€¡','Ð¸ Ð¾ÐºÐ¾Ð»Ð½Ð¾ÑÑâ€šÑâ€šÐ°',4],
    ];
    $st5 = $pdo->prepare("INSERT INTO stats (svalue,slabel,sort_order) VALUES (?,?,?)");
    foreach ($stats as $s) $st5->execute($s);
    $seed = [
        ['kuhnya-po-porachka','ÐšÑƒÑâ€¦Ð½Ñ Ð¿Ð¾ Ð¿Ð¾Ñâ‚¬ÑŠÑâ€¡ÐºÐ°','ÐšÑƒÑâ€¦Ð½Ð¸','Ð®Ð»Ð¸ 2024','Ðâ€Ð¾Ð±Ñâ‚¬Ð¸Ñâ€¡','ÐœÐ¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° ÐºÑƒÑâ€¦Ð½Ñ Ð¿Ð¾ Ð¿Ð¾Ñâ‚¬ÑŠÑâ€¡ÐºÐ° Ñ Ð¾ÑÑâ€šÑâ‚¬Ð¾Ð², Ð²Ð³Ñâ‚¬Ð°Ð´ÐµÐ½Ð¸ ÑˆÐºÐ°Ñâ€žÐ¾Ð²Ðµ Ð¸ Ð·Ð°Ð²ÑŠÑâ‚¬ÑˆÐ²Ð°Ñâ€°Ð¸ Ð¿Ð°Ð½ÐµÐ»Ð¸.','/assets/media/kitchen-2.jpg',['/assets/media/kitchen-2.jpg','/assets/media/kitchen-1.jpg','/assets/media/kitchen-3.jpg','/assets/media/kitchen-4.jpg']],
        ['moderna-kuhnya','ÐœÐ¾Ð´ÐµÑâ‚¬Ð½Ð° ÐºÑƒÑâ€¦Ð½Ñ','ÐšÑƒÑâ€¦Ð½Ð¸','Ð®Ð½Ð¸ 2024','Ðâ€˜Ð°Ð»Ñâ€¡Ð¸Ðº','ÐœÐ¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° Ð¼Ð¾Ð´ÐµÑâ‚¬Ð½Ð° ÐºÑƒÑâ€¦Ð½Ñ Ñ Ð¾ÑÑâ€šÑâ‚¬Ð¾Ð², Ð²Ð³Ñâ‚¬Ð°Ð´ÐµÐ½Ð¸ ÑˆÐºÐ°Ñâ€žÐ¾Ð²Ðµ Ð¸ Ð·Ð°Ð²ÑŠÑâ‚¬ÑˆÐ²Ð°Ñâ€°Ð¸ Ð¿Ð°Ð½ÐµÐ»Ð¸.','/assets/media/kitchen-3.jpg',['/assets/media/kitchen-3.jpg','/assets/media/kitchen-4.jpg','/assets/media/kitchen-1.jpg']],
        ['spalnya-i-garderob','Ð¡Ð¿Ð°Ð»Ð½Ñ Ð¸ Ð³Ð°Ñâ‚¬Ð´ÐµÑâ‚¬Ð¾Ð±','Ð¡Ð¿Ð°Ð»Ð½Ð¸','ÐÐ¿Ñâ‚¬Ð¸Ð» 2024','Ðâ€Ð¾Ð±Ñâ‚¬Ð¸Ñâ€¡','Ð¡Ð³Ð»Ð¾Ð±ÑÐ²Ð°Ð½Ðµ Ð½Ð° ÑÐ¿Ð°Ð»Ð½Ñ, Ð½Ð¾Ñâ€°Ð½Ð¸ ÑˆÐºÐ°Ñâ€žÑâ€¡ÐµÑâ€šÐ° Ð¸ Ð³Ð°Ñâ‚¬Ð´ÐµÑâ‚¬Ð¾Ð± Ñ Ð¿Ð»ÑŠÐ·Ð³Ð°Ñâ€°Ð¸ ÑÐµ Ð²Ñâ‚¬Ð°Ñâ€šÐ¸.','/assets/media/bedroom-1.jpg',['/assets/media/bedroom-1.jpg','/assets/media/bedroom-2.jpg','/assets/media/living-3.jpg']],
        ['garderob-po-razmer','Ðâ€œÐ°Ñâ‚¬Ð´ÐµÑâ‚¬Ð¾Ð± Ð¿Ð¾ Ñâ‚¬Ð°Ð·Ð¼ÐµÑâ‚¬','Ðâ€œÐ°Ñâ‚¬Ð´ÐµÑâ‚¬Ð¾Ð±Ð¸','ÐœÐ°Ñâ‚¬Ñâ€š 2024','Ðâ€™Ð°Ñâ‚¬Ð½Ð°','ÐœÐ¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° Ð²Ð¸ÑÐ¾Ðº Ð³Ð°Ñâ‚¬Ð´ÐµÑâ‚¬Ð¾Ð± Ð¿Ð¾ Ñâ‚¬Ð°Ð·Ð¼ÐµÑâ‚¬ Ñ Ð¿Ð»Ð°Ð²Ð½Ð¾ Ð·Ð°Ñâ€šÐ²Ð°Ñâ‚¬ÑÐ½Ðµ Ð¸ Ð²ÑŠÑâ€šÑâ‚¬ÐµÑˆÐ½Ð¾ Ñâ‚¬Ð°Ð·Ð¿Ñâ‚¬ÐµÐ´ÐµÐ»ÐµÐ½Ð¸Ðµ.','/assets/media/bedroom-2.jpg',['/assets/media/bedroom-2.jpg','/assets/media/bedroom-1.jpg','/assets/media/kitchen-4.jpg']],
        ['sektsiya-za-dnevna','Ð¡ÐµÐºÑâ€ Ð¸Ñ Ð·Ð° Ð´Ð½ÐµÐ²Ð½Ð°','Ðâ€Ñâ‚¬ÑƒÐ³Ð¸','Ð¤ÐµÐ²Ñâ‚¬ÑƒÐ°Ñâ‚¬Ð¸ 2024','Ðâ€Ð¾Ð±Ñâ‚¬Ð¸Ñâ€¡','ÐœÐ¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° ÑÐµÐºÑâ€ Ð¸Ñ Ð·Ð° Ð´Ð½ÐµÐ²Ð½Ð° Ñ Ð¢Ðâ€™ Ð¿Ð°Ð½ÐµÐ», ÑˆÐºÐ°Ñâ€žÐ¾Ð²Ðµ Ð¸ ÑÐºÑâ‚¬Ð¸Ñâ€š Ð¼Ð¾Ð½Ñâ€šÐ°Ð¶.','/assets/media/living-1.jpg',['/assets/media/living-1.jpg','/assets/media/living-2.jpg','/assets/media/living-3.jpg']],
        ['ofis-obzavezhdane','ÐžÑâ€žÐ¸Ñ Ð¾Ð±Ð·Ð°Ð²ÐµÐ¶Ð´Ð°Ð½Ðµ','Ðâ€Ñâ‚¬ÑƒÐ³Ð¸','Ð¯Ð½ÑƒÐ°Ñâ‚¬Ð¸ 2024','Ðâ€Ð¾Ð±Ñâ‚¬Ð¸Ñâ€¡','ÐœÐ¾Ð½Ñâ€šÐ°Ð¶ Ð½Ð° Ð¾Ñâ€žÐ¸Ñ Ð±ÑŽÑâ‚¬Ð¾, ÑˆÐºÐ°Ñâ€žÐ¾Ð²Ðµ, ÐµÑâ€šÐ°Ð¶ÐµÑâ‚¬ÐºÐ¸ Ð¸ ÐºÐ¾Ð½Ñâ€žÐµÑâ‚¬ÐµÐ½Ñâ€šÐ½Ð° Ð¼Ð°ÑÐ°.','/assets/media/living-3.jpg',['/assets/media/living-3.jpg','/assets/media/living-2.jpg','/assets/media/kitchen-4.jpg']],
    ];
    $st = $pdo->prepare("INSERT INTO projects (slug,title,category,pdate,location,description,cover,gallery) VALUES (?,?,?,?,?,?,?,?)");
    foreach ($seed as $p) $st->execute([$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6],json_encode($p[7])]);
}
